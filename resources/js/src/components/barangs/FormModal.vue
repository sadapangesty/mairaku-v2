<template>
  <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
      <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Barang' : 'Tambah Barang' }}</h3>
      <div class="grid gap-4">
        <input v-model="form.nama_barang" type="text" placeholder="Nama Barang" class="form-input" />

        <select v-model="form.id_kategori" class="form-input">
          <option value="">Pilih Kategori</option>
          <option v-for="item in kategoris" :key="item.id_kategori" :value="item.id_kategori">{{ item.nama }}</option>
        </select>

        <select v-model="form.id_jenis" class="form-input">
          <option value="">Pilih Jenis</option>
          <option v-for="item in jeniss" :key="item.id_jenis" :value="item.id_jenis">{{ item.nama }}</option>
        </select>

        <input v-model="form.sku" type="text" placeholder="SKU" class="form-input" disabled />

        <input v-model="form.harga" type="number" placeholder="Harga" class="form-input" />

        <input v-model="form.link_barang" type="text" placeholder="Link Barang" class="form-input" />

        <input type="file" accept="image/*" class="form-input" @change="$emit('handleImageUpload', $event)" />
        <div v-if="imagePreview" class="mt-2">
          <img :src="imagePreview" class="w-20 h-20 object-cover rounded-full" />
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
        <button class="btn btn-primary" @click="$emit('save')">{{ isEditing ? 'Update' : 'Save' }}</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps(['showModal', 'isEditing', 'form', 'kategoris', 'jeniss', 'imagePreview']);
defineEmits(['close', 'save', 'handleImageUpload']);
</script>
