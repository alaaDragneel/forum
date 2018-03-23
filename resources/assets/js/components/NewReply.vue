<template>
    <div>
            <div v-if="signedIn">
                <div class="form-group">
                    <textarea 
                        name="body" 
                        id="body" 
                        class="form-control" 
                        placeholder="Have Something To Say ?" 
                        rows="5"
                        required
                        v-model="body"></textarea>
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

export default {
    data () { 
        return {
            body: '',
            disabled: false,
            endpoint: location.pathname + "/replies"
        };
    },
    computed: {
        signedIn() {
            return window.App.signedIn;
        },
        state () {
            return this.disabled ? 'Loading ...' : 'Post';
        }
    },
    methods: {
        addReply() {
            this.disabled = true;
            axios.post(this.endpoint, { body: this.body })
                .then( ({ data }) => {
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