<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body" v-model="body" placeholder="Have Something To Say ?" id="body" class="form-control" rows="5"></textarea>
            </div>
            <button class="btn btn-success" @click="addReply" :disabled="disabled" v-text="state">Post</button>
        </div>
        <div v-else>
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Want Join The Discussion</h4>
                <p>Please <a href="/login">Login</a> To Join This Discussion</p>
            </div>
        </div>


    </div>
</template>

<script>
	import 'at.js';
	import 'jquery.caret';

	export default {
		data () {
			return {
				body: '',
				disabled: false,
				endpoint: location.pathname + "/replies"
			};
		},
		computed: {
			state () {
				return this.disabled ? 'Loading ...' : 'Post';
			}
		},
		mounted () {
			$('#body').atwho({
				at: '@',
				delay: 750,
				callbacks: {
					remoteFilter (query, callback) {
						$.getJSON('/api/users', { name: query }, usernames => {
							callback(usernames);
						});
					}
				}
			});
		},
		methods: {
			addReply () {
				this.disabled = true;
				axios.post(this.endpoint, { body: this.body })
				.then(({ data }) => {
					this.disabled = false;
					this.body = '';
					flash('Your Reply Has Been Left!');
					this.$emit('reply-created', data);
				})
				.catch(error => {
					this.disabled = false;
					flash(error.response.data, 'danger');
				});
			},
		}
	};
</script>