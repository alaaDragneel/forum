<template>
    <!-- Styles and name attribute Can be Found in The Parent Avatar Form and it Work Like This If It i a native element like this a single native element -->
    <input type="file" required accept="image/*" @change="onChange">
</template>

<script>
    export default {
        methods: {
            onChange(e) {
                if (!e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;
                    this.$emit('loaded', {src, file});
                };
            }
        }
    }
</script>