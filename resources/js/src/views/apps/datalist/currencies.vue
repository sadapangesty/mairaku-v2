<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Mata Uang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary  w-full sm:flex-1" @click="openAddModal()">

            Add Mata Uang
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
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteCurrency(data.value)" title="Delete">
                ❌
              </button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Mata Uang' : 'Tambah Mata Uang' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.kode" type="text" placeholder="Kode (e.g. USD)" class="form-input" />
          <input v-model="form.nama" type="text" placeholder="Nama (e.g. Dolar Amerika)" class="form-input" />
          <input v-model="form.simbol" type="text" placeholder="Simbol (e.g. $)" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveCurrency">{{ isEditing ? 'Update' : 'Save' }}</button>
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
      id_currency: null as number | null,
      kode: '',
      nama: '',
      simbol: '',

    });

    const cols = ref([
      { field: 'no', title: 'No', sortable: false },
      { field: 'kode', title: 'Kode', sortable: true },
      { field: 'nama', title: 'Nama', sortable: true },
      { field: 'simbol', title: 'Simbol', sortable: true },
      { field: 'actions', title: 'Action', sortable: false },
    ]);

    const rows = ref<any[]>([]);

    const loadCurrencies = async () => {
      try {
        const response = await axios.get('/api/currencies');
        console.log('Data dari API:', response.data);

        rows.value = response.data.map((item: any, index: number) => ({
          ...item,
          no: index + 1,
        }));
        console.log('Rows setelah mapping:', rows.value);
      } catch (error) {
        console.error('Gagal memuat data:', error);
        Swal.fire('Error', 'Gagal memuat data mata uang.', 'error');
      }
    };


    onMounted(() => loadCurrencies());

    const openAddModal = () => {
      isEditing.value = false;
      form.value = { id_currency: null, kode: '', nama: '', simbol: ''};
      showModal.value = true;
    };

    const openEditModal = (currency: any) => {
      isEditing.value = true;
      form.value = {
        id_currency: currency.id_currency,
        kode: currency.kode,
        nama: currency.nama,
        simbol: currency.simbol,

      };
      showModal.value = true;
    };

    const closeModal = () => { showModal.value = false; };

    const saveCurrency = async () => {
      loading.value = true;
      try {
        const payload = { ...form.value }; // <<< Cukup seperti ini
        if (isEditing.value) {
          await axios.put(`/api/currencies/${form.value.id_currency}`, payload);
          Swal.fire('Success', 'Data mata uang diperbarui!', 'success');
        } else {
          await axios.post('/api/currencies', payload);
          Swal.fire('Success', 'Mata uang baru ditambahkan!', 'success');
        }
        await loadCurrencies();
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

    const deleteCurrency = async (currency: any) => {
      if (!currency.id_currency) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
      const confirmResult = await Swal.fire({
        title: `Hapus mata uang "${currency.nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
      });
      if (!confirmResult.isConfirmed) return;
      loading.value = true;
      try {
        await axios.delete(`/api/currencies/${currency.id_currency}`);
        Swal.fire('Deleted!', 'Mata uang berhasil dihapus.', 'success');
        await loadCurrencies();
      } catch (error) {
        console.error('Delete error:', error);
        Swal.fire('Error', 'Gagal menghapus mata uang.', 'error');
      } finally {
        loading.value = false;
      }
    };
  </script>
