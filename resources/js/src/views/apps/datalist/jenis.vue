<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Jenis Barang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary w-full sm:flex-1" @click="openAddModal">
            Add Jenis
          </button>
          <input v-model="search" type="text" class="form-input w-full sm:flex-1" placeholder="Search..." />
        </div>
      </div>

      <div class="datatable">
        <vue3-datatable
          :rows="rows"
          :columns="cols"
          :search="search"
          :totalRows="rows.length"
          :sortable="true"
          sortColumn="no"
          skin="whitespace-nowrap bh-table-hover"
        >
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">
                ✏️
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteJenis(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Jenis' : 'Tambah Jenis' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama" type="text" placeholder="Nama Jenis" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveJenis">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axios from 'axios';
import Swal from 'sweetalert2';


const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_jenis: null as number | null,
  nama: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama', title: 'Nama Jenis', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);

const loadJenis = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/jenis');
    rows.value = response.data.map((item: any, index: number) => ({
      ...item,
      no: index + 1,
    }));
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Data gagal dimuat. Silakan cek koneksi atau coba lagi.', 'error');
  } finally {
    loading.value = false;
  }
};

onMounted(() => loadJenis());

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_jenis: null, nama: '' };
  showModal.value = true;
};

const openEditModal = (item: any) => {
  isEditing.value = true;
  form.value = {
    id_jenis: item.id_jenis,
    nama: item.nama,
  };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveJenis = async () => {
  loading.value = true;
  try {
    const payload = { ...form.value };
    if (isEditing.value) {
      await axios.put(`/api/jenis/${form.value.id_jenis}`, payload);
      Swal.fire('Success', 'Data berhasil diperbarui!', 'success');
    } else {
      await axios.post('/api/jenis', payload);
      Swal.fire('Success', 'Data berhasil ditambahkan!', 'success');
    }
    await loadJenis();
    closeModal();
  } catch (error: any) {
    console.error(error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan data';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteJenis = async (item: any) => {
  if (!item.id_jenis) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirm = await Swal.fire({
    title: `Hapus jenis "${item.nama}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirm.isConfirmed) return;

  loading.value = true;
  try {
    await axios.delete(`/api/jenis/${item.id_jenis}`);
    Swal.fire('Deleted!', 'Data berhasil dihapus.', 'success');
    await loadJenis();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus data.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>
