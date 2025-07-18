<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Bank</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary" @click="openAddModal()">
            Add Bank
          </button>
          <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
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
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteBank(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Bank' : 'Tambah Bank' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama_bank" type="text" placeholder="Nama Bank" class="form-input" />
          <input v-model="form.atas_nama" type="text" placeholder="Atas Nama" class="form-input" />
          <input v-model="form.no_rekening" type="text" placeholder="No Rekening" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveBank">{{ isEditing ? 'Update' : 'Save' }}</button>
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
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Bank' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_bank: null as number | null,
  nama_bank: '',
  atas_nama: '',
  no_rekening: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama_bank', title: 'Nama Bank', sortable: true },
  { field: 'atas_nama', title: 'Atas Nama', sortable: true },
  { field: 'no_rekening', title: 'No Rekening', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);

const loadBanks = async () => {
  try {
    const response = await axios.get('/api/banks');
    console.log(response.data); // debug: pastikan ini array
    rows.value = response.data.map((item, index) => ({
      ...item,
      no: index + 1,
    }));
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Gagal memuat data bank.', 'error');
  }
};
onMounted(() => loadBanks());

const openAddModal = () => {
  isEditing.value = false;
  form.value = {
    id_bank: null,
    nama_bank: '',
    atas_nama: '',
    no_rekening: '',
  };
  showModal.value = true;
};

const openEditModal = (bank: any) => {
  isEditing.value = true;
  form.value = {
    id_bank: bank.id_bank,
    nama_bank: bank.nama_bank,
    atas_nama: bank.atas_nama,
    no_rekening: bank.no_rekening,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveBank = async () => {
  loading.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/banks/${form.value.id_bank}`, form.value);
      Swal.fire('Success', 'Data bank diperbarui!', 'success');
    } else {
      await axios.post('/api/banks', form.value);
      Swal.fire('Success', 'Bank baru ditambahkan!', 'success');
    }
    await loadBanks();
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

const deleteBank = async (bank: any) => {
  if (!bank.id_bank) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirmResult = await Swal.fire({
    title: `Hapus bank "${bank.nama_bank}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirmResult.isConfirmed) return;
  loading.value = true;
  try {
    await axios.delete(`/api/banks/${bank.id_bank}`);
    Swal.fire('Deleted!', 'Bank berhasil dihapus.', 'success');
    await loadBanks();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus bank.', 'error');
  } finally {
    loading.value = false;
  }
};
</script>
