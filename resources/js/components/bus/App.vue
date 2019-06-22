<template>
    <div>
        <div>
            <Foo></Foo>
        </div>
        <hr>
        <div>
            <Bar></Bar>
        </div>
    </div>
</template>

<script>
    import Foo from './Foo.vue'
    import Bar from './Bar.vue'
    export default {   
        components: {
            Foo,
            Bar
        }
    }
</script>