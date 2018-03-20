<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],
        data() {
            return {
                activeState: this.active
            };
        },
        computed: {
            classes () {
                return ['btn', this.activeState ? 'btn-primary' : 'btn-default']
            }
        },
        methods: {
            subscribe() {
                let requestType = this.activeState ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscriptions');
                this.activeState = ! this.activeState;
            }
        }
    }
</script>