<template>
  <div>
    <List />
  </div>
</template>

<script setup>
import List from '@/components/customers/List.vue';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Customers' });
</script>
