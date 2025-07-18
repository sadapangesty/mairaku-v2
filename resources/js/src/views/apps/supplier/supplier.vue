<template>
  <div>
    <Supplierslocal />
    <Suppliersonline />

  </div>
</template>

<script setup lang="ts">
import Supplierslocal from './suppliers-local.vue';
import Suppliersonline from './suppliers-online.vue';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Supplier' });
</script>
