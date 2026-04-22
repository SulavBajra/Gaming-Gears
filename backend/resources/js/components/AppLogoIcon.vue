<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logo from '../../assets/dragon-head.svg';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img :src="logo" alt="logo" v-bind="$attrs" />
</template>
