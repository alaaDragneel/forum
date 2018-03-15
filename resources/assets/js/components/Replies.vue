<template>
  <div>
      <div v-for="(reply, index) in items" :key="index">
          <reply :data="reply" @reply-deleted="remove(index)"></reply>   
      </div>
      <new-reply :endpoint="endpoint" @reply-created="add"></new-reply>
  </div>
</template>

<script>
import Reply from "./Reply.vue";
import NewReply from "./NewReply.vue";
export default {
  props: ["data"],
  components: {
    Reply,
    NewReply
  },
  data() {
    return {
      items: this.data,
      endpoint: location.pathname + '/replies'
    };
  },
  methods: {
    add(reply) {
      this.items.push(reply);
        this.$emit("add-reply");
    },
    remove(index) {
      this.items.splice(index, 1);
      this.$emit("remove-reply");
      flash("Reply Was Deleted");
    }
  }
};
</script>
