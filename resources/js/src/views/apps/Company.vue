<template>
  <div>
    <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
      <h5 class="font-semibold text-lg dark:text-white-light">Data Companies</h5>
      <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
        <button type="button" class="btn btn-primary" @click="openAddModal()">
          Add Company
        </button>
        <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div
        v-for="item in filteredRows"
        :key="item.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col"
      >
        <img
          :src="item.logo ? `/assets/images/${item.logo}` : '/assets/images/default-logo.png'"
          class="w-full h-32 object-cover rounded mb-2"
        />
        <div class="flex-1">
          <h4 class="font-semibold text-base mb-1">{{ item.name }}</h4>
          <p class="text-xs text-gray-500 mb-1">Email: {{ item.email || '-' }}</p>
          <p class="text-xs text-gray-500 mb-1">Phone: {{ item.phone || '-' }}</p>
          <p class="text-xs text-gray-500">{{ item.address || '-' }}</p>
        </div>
        <div class="flex justify-between mt-2">
          <button class="btn btn-xs btn-outline-primary" @click="openEditModal(item)">✏️ Edit</button>
          <button class="btn btn-xs btn-outline-danger" @click="deleteCompany(item)">❌ Delete</button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Company' : 'Tambah Company' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.name" type="text" placeholder="Name" class="form-input" />
          <input v-model="form.email" type="email" placeholder="Email" class="form-input" />
          <input v-model="form.phone" type="text" placeholder="Phone" class="form-input" />
          <textarea v-model="form.address" placeholder="Address" class="form-input"></textarea>

          <input type="file" accept="image/*" class="form-input" @change="handleImageUpload" />
          <div v-if="imagePreview" class="mt-2">
            <img :src="imagePreview" class="w-20 h-20 object-cover rounded-full" />
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveCompany">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Companies' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const imagePreview = ref('');
const loading = ref(false);
const rows = ref<any[]>([]);

const form = ref({
  id: null as number | null,
  name: '',
  email: '',
  phone: '',
  address: '',
  logo: '',
});

const filteredRows = computed(() =>
  rows.value.filter(item =>
    item.name.toLowerCase().includes(search.value.toLowerCase())
    || (item.email || '').toLowerCase().includes(search.value.toLowerCase())
    || (item.phone || '').toLowerCase().includes(search.value.toLowerCase())
  )
);

const loadCompanies = async () => {
  try {
    const response = await axios.get('/api/companies');
    rows.value = response.data.data.data; // sesuaikan jika pakai pagination
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Gagal memuat data companies.', 'error');
  }
};

onMounted(loadCompanies);

const openAddModal = () => {
  isEditing.value = false;
  imagePreview.value = '';
  form.value = { id: null, name: '', email: '', phone: '', address: '', logo: '' };
  showModal.value = true;
};

const openEditModal = (company: any) => {
  isEditing.value = true;
  form.value = {
    id: company.id,
    name: company.name,
    email: company.email,
    phone: company.phone,
    address: company.address,
    logo: company.logo,
  };
  imagePreview.value = company.logo ? `/assets/images/${company.logo}` : '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveCompany = async () => {
  loading.value = true;
  try {
    const payload = new FormData();
    payload.append('name', form.value.name);
    payload.append('email', form.value.email || '');
    payload.append('phone', form.value.phone || '');
    payload.append('address', form.value.address || '');
    if (form.value.logo instanceof File) {
      payload.append('logo', form.value.logo);
    }

    if (isEditing.value && form.value.id) {
      await axios.post(`/api/companies/${form.value.id}?_method=PUT`, payload);
    } else {
      await axios.post('/api/companies', payload);
    }

    Swal.fire('Success', 'Data berhasil disimpan!', 'success');
    await loadCompanies();
    closeModal();
  } catch (error: any) {
    console.error('Save error:', error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan data';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteCompany = async (company: any) => {
  if (!company.id) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirm = await Swal.fire({
    title: `Hapus company "${company.name}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirm.isConfirmed) return;

  loading.value = true;
  try {
    await axios.delete(`/api/companies/${company.id}`);
    Swal.fire('Deleted!', 'Company berhasil dihapus.', 'success');
    await loadCompanies();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus company.', 'error');
  } finally {
    loading.value = false;
  }
};

const handleImageUpload = (e: any) => {
  const file = e.target.files[0];
  if (file) {
    form.value.logo = file;
    const reader = new FileReader();
    reader.onload = (ev) => {
      imagePreview.value = ev.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};
</script>
