<template>
    <div>
        <h1>Counter from Foo: {{ counter }}</h1>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                counter: 0
            }
        },
        created () {
            this.$bus.on('increaseCounter', value => {
                this.counter = value
            })
        }
    }
</script>