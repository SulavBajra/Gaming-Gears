<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logo from '../../assets/logo.png';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img :src="logo" :class="className" alt="logo" v-bind="$attrs" />
</template>
