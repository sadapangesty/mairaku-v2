<template>
  <div>
    <List />
  </div>
</template>

<script setup>
import List from '@/components/barangs/list.vue';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Barang' });
</script>
