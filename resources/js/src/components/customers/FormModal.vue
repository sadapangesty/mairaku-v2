<template>
  <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
      <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Customer' : 'Tambah Customer' }}</h3>
      <div class="grid gap-4">
        <input v-model="form.name" type="text" placeholder="Nama" class="form-input" />
        <input v-model="form.email" type="email" placeholder="Email" class="form-input" />
        <input v-model="form.phone" type="text" placeholder="Telepon" class="form-input" />
        <textarea v-model="form.address" placeholder="Alamat" class="form-input" rows="2"></textarea>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
        <button class="btn btn-primary" @click="$emit('save')">{{ isEditing ? 'Update' : 'Save' }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps(['showModal', 'isEditing', 'form']);
defineEmits(['close', 'save']);
</script>
