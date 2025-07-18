<template>
  <div>
    <CurrencyPanel />
    <RatePanel />
    <JenisPanel/>
    <KategoriPanel/>
  </div>
</template>

<script setup lang="ts">
import CurrencyPanel from './currencies.vue';
import RatePanel from './rate.vue';
import JenisPanel from './jenis.vue';
import KategoriPanel from './kategori.vue';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data List' });

</script>
