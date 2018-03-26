<template>
    <div>
        <div class="level">
            <img :src="avatar" :alt="user.name" :title="user.name" class="mr-1" width="50" height="50">
            <h1> {{ user.name }}
                <small> Since {{ ago }} </small>
            </h1>

        </div>

        <form v-if="canUpdate" method="post" enctype="multipart/form-data">
            <image-upload name="avatar" @loaded="onLoad"></image-upload>

        </form>


    </div>
</template>

<script>
    import moment from 'moment';
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],
        components: {
            ImageUpload
        },
        data() {
            return {
                avatar: this.user.avatar_path
            };
        },
        computed: {
            canUpdate() {
                return this.authorized(user => user.id === this.user.id)
            },
            ago() {
                return moment(this.user.created_at).fromNow();
            }
        },
        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                // Persist To The Server
                this.persist(avatar.file);
            },
            persist(avatar) {
                let data = new FormData();
                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar Uploaded!'))
            }
        }
    }
</script>