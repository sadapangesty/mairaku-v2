<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Kategori Barang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary w-full sm:flex-1" @click="openAddModal()">
            Add Kategori
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
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteKategori(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama" type="text" placeholder="Nama Kategori" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveKategori">{{ isEditing ? 'Update' : 'Save' }}</button>
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
  id_kategori: null as number | null,
  nama: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama', title: 'Nama Kategori', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);

const loadKategoris = async () => {
  try {
    const response = await axios.get('/api/kategoris');
    console.log('Data dari API:', response.data);

    rows.value = response.data.map((item: any, index: number) => ({
      ...item,
      no: index + 1,
    }));
    console.log('Rows setelah mapping:', rows.value);
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Gagal memuat data kategori.', 'error');
  }
};

onMounted(() => loadKategoris());

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_kategori: null, nama: '' };
  showModal.value = true;
};

const openEditModal = (kategori: any) => {
  isEditing.value = true;
  form.value = {
    id_kategori: kategori.id_kategori,
    nama: kategori.nama,
  };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveKategori = async () => {
  loading.value = true;
  try {
    const payload = { ...form.value };
    if (isEditing.value) {
      await axios.put(`/api/kategoris/${form.value.id_kategori}`, payload);
      Swal.fire('Success', 'Data kategori diperbarui!', 'success');
    } else {
      await axios.post('/api/kategoris', payload);
      Swal.fire('Success', 'Kategori baru ditambahkan!', 'success');
    }
    await loadKategoris();
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

const deleteKategori = async (kategori: any) => {
  if (!kategori.id_kategori) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirmResult = await Swal.fire({
    title: `Hapus kategori "${kategori.nama}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirmResult.isConfirmed) return;

  loading.value = true;
  try {
    await axios.delete(`/api/kategoris/${kategori.id_kategori}`);
    Swal.fire('Deleted!', 'Kategori berhasil dihapus.', 'success');
    await loadKategoris();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus kategori.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>
