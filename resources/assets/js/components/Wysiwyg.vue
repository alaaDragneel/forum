<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor ref="trix" input="trix" :placeholder="placeholder" :id="id"></trix-editor>
    </div>
</template>

<script>
	import Trix from 'trix';

	export default {
		props: [ 'name', 'value', 'placeholder', 'id', 'shouldClear' ],
		data () {
			return {};
		},
		mounted () {
			this.$refs.trix.addEventListener('trix-change', e => {
				this.$emit('input', e.target.innerHTML)
			});
			this.$watch('shouldClear', () => {
				this.$refs.trix.value = '';
			});
		},
		methods: {}
	}
</script>