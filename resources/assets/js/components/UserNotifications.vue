<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">

            <li v-for="notification in notifications">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>

        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: [] };
        },
        created () {
            axios.get("/profiles/"+ window.App.user.name +"/notifications")
                .then(({ data }) => this.notifications = data);
        },
        methods: {
            markAsRead(notification) {
                axios.delete("/profiles/" + window.App.user.name + "/notifications/" + notification.id);
            }
        }
    }
</script>