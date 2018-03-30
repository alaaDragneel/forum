<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @reply-deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>


        <div role="alert" class="alert alert-info" v-if="$parent.locked">
            <h4 class="alert-heading">Locked Thread!</h4>
            <p>
                This Thread Has Been Locked. No More Replies Are Allowed.
            </p>
        </div>

        <new-reply @reply-created="add" v-else></new-reply>

    </div>
</template>

<script>
    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";
    import collection from "../mixins/Collection";

    export default {
        components: {Reply, NewReply},
        mixins: [collection],
        data() {
            return {dataSet: false};
        },
        created() {
            this.fetch();
        },
        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
                window.scrollTo(0, 0);
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            }
        }
    };
</script>
