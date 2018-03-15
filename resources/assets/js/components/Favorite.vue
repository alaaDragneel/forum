<template>
    <button :class="classes" @click="toggle" >
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favoriteCount"></span>
    </button>  
</template>

<script>
export default {
  props: ["reply"],
  data() {
    return {
      favoriteCount: this.reply.favoritesCount,
      isFavorited: this.reply.isFavorited
      //   reply: this.reply
    };
  },
  computed: {
    classes() {
      //btn btn-danger btn-sm
      return ['btn btn-sm', this.isFavorited ? 'btn-primary' : 'btn-default'];
    },
    endpoint() {
      return '/replies/' + this.reply.id + '/favorites';
    }
  },
  methods: {
    toggle() {
      this.isFavorited ? this.unFavorite() : this.favorite();
    },
    favorite() {
      axios.post(this.endpoint); // create the endpoint
      this.isFavorited = true;
      this.favoriteCount++;
      flash('You Favorite The Reply');
    },
    unFavorite() {
      axios.delete(this.endpoint); // create the endpoint
      this.isFavorited = false;
      this.favoriteCount--;
      flash('You Unfavorite The Reply');
    },
  }
};
</script>