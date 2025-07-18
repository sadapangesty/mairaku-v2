<template>
  <div class="panel pb-8 mt-4 rounded-lg shadow-md bg-white dark:bg-gray-900">
    <div class="px-6 pt-6">
      <!-- Header -->
      <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
        <div class="lg:w-1/2 w-full flex justify-center lg:justify-start">
          <img src="/assets/images/logo.svg" alt="Logo" class="w-24 mb-4" />
        </div>
        <div class="lg:w-1/2 w-full text-center lg:text-right">
          <h1
            class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide mb-2"
          >
            QUOTATION
          </h1>
          <div
            class="text-sm text-gray-600 dark:text-gray-400 space-y-1 leading-relaxed"
          >
            <p>Jl. Lingkungan III GG LINGGA 5, RT.4/RW.3, Kalideres,</p>
            <p>Jakarta Barat, DKI Jakarta 11820</p>
            <p class="text-blue-600 dark:text-blue-400">Mairaku@gmail.com</p>
            <p>+62 882-8974-9869</p>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <hr class="my-6 border-t border-gray-300 dark:border-gray-700" />

      <!-- Info Quotation & Bill To -->
      <div
        class="flex flex-col md:flex-row justify-between gap-6 text-sm text-gray-700 dark:text-gray-300"
      >
        <!-- Info Quotation -->
        <div class="md:w-1/2 space-y-2">
          <div class="font-semibold text-gray-800 dark:text-white mb-1">
            Info Quotation
          </div>

          <div class="flex items-center gap-3">
            <label class="w-32 text-xs font-medium">No. Quotation</label>
            <input
              v-model="form.invoiceNo"
              type="text"
              class="form-input flex-1"
              readonly
            />
          </div>

          <div class="flex items-center gap-3">
            <label class="w-32 text-xs font-medium">Tanggal</label>
            <input
              id="date"
              type="date"
              class="form-input flex-1"
              v-model="form.date"
            />
          </div>

          <div class="flex items-center gap-3">
            <label class="w-32 text-xs font-medium">Masa Berlaku</label>
            <input
              id="dueDate"
              type="date"
              class="form-input flex-1"
              v-model="form.dueDate"
            />
          </div>
        </div>

        <!-- Bill To -->
        <div class="md:w-1/2 space-y-2">
          <div class="flex items-center justify-between mb-1">
            <span class="font-semibold text-gray-800 dark:text-white"
              >Bill To</span
            >
            <button
              @click="showCustomerModal = true"
              type="button"
              class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
              + Add Customer
            </button>
          </div>

          <div class="flex items-center gap-2">
            <label class="w-32 text-xs font-medium">Nama</label>
            <select
              class="form-select flex-1"
              v-model="form.customer.idCustomer"
              @change="selectCustomer"
            >
              <option value="" selected>-- Pilih Pelanggan --</option>
              <option
                v-for="customer in customers"
                :key="customer.idCustomer"
                :value="String(customer.idCustomer)"
              >
                {{ customer.name }}
              </option>
            </select>
          </div>

          <div class="flex items-center gap-2">
            <label class="w-32 text-xs font-medium">Phone</label>
            <input
              v-model="form.customer.phone"
              type="text"
              class="form-input flex-1"
              readonly
            />
          </div>

          <div class="flex items-center gap-2">
            <label class="w-32 text-xs font-medium">Alamat</label>
            <textarea
              v-model="form.customer.address"
              type="text"
              class="form-input flex-1"
              rows="2"
              readonly
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Daftar Item -->
      <div class="mt-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
            Daftar Item
          </h2>
          <button
            @click="showCatalogModal = true"
            class="text-sm px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition"
          >
            + Tambah Item
          </button>
        </div>

        <div class="overflow-auto">
          <table
            class="w-full text-sm border border-gray-300 dark:border-gray-700"
          >
            <thead
              class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200"
            >
              <tr>
                <th class="p-2 border">Item</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Tipe Pengiriman</th>
                <th class="p-2 border">Panjang (m)</th>
                <th class="p-2 border">Lebar (m)</th>
                <th class="p-2 border">Harga Asli</th>
                <th class="p-2 border">Harga Jual</th>
                <th class="p-2 border">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in form.items"
                :key="index"
                class="bg-white dark:bg-gray-900"
              >
                <td class="border p-2">
                  <input
                    v-model="item.name"
                    class="form-input w-full"
                    placeholder="Nama Item"
                  />
                </td>
                <td class="border p-2">
                  <input
                    v-model.number="item.qty"
                    type="number"
                    min="1"
                    class="form-input w-full"
                  />
                </td>
                <td class="border p-2">
                  <select
                    v-model="item.shipping_type"
                    class="form-input w-full"
                  >
                    <option value="">Pilih tipe</option>
                    <option value="laut_kecil">Laut Kecil</option>
                    <option value="laut_besar">Laut Besar</option>
                  </select>
                </td>
                <td class="border p-2">
                  <input
                    v-model.number="item.length"
                    type="number"
                    min="0"
                    :disabled="item.shipping_type !== 'laut_besar'"
                    class="form-input w-full"
                    placeholder="Panjang"
                  />
                </td>
                <td class="border p-2">
                  <input
                    v-model.number="item.width"
                    type="number"
                    min="0"
                    :disabled="item.shipping_type !== 'laut_besar'"
                    class="form-input w-full"
                    placeholder="Lebar"
                  />
                </td>
                <td class="border p-2">
                  <input
                    v-model.number="item.original_price"
                    type="number"
                    min="0"
                    class="form-input w-full"
                    placeholder="Harga Asli"
                  />
                </td>
                <td class="border p-2">
                  <input
                    v-model.number="item.selling_price"
                    type="number"
                    min="0"
                    class="form-input w-full"
                    placeholder="Harga Jual"
                  />
                </td>
                <td class="border p-2 text-right font-semibold">
                  {{ formatCurrency(calculateTotal(item)) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Ringkasan -->
      <div
        class="mt-6 md:w-1/2 ml-auto space-y-2 text-sm text-gray-800 dark:text-white"
      >
        <div class="flex justify-between">
          <span>Subtotal</span>
          <span>{{ formatCurrency(subtotal) }}</span>
        </div>
        <div class="flex justify-between">
          <span>Diskon</span>
          <input
            v-model.number="form.discount"
            type="number"
            class="form-input w-28 text-right"
            min="0"
          />
        </div>
        <div class="flex justify-between">
          <span>Pajak (10%)</span>
          <span>{{ formatCurrency(tax) }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2">
          <span>Total</span>
          <span>{{ formatCurrency(total) }}</span>
        </div>
      </div>

      <!-- Catatan -->
      <div class="mt-8 text-sm">
        <label class="block mb-1 text-gray-700 dark:text-gray-200 font-medium"
          >Catatan Tambahan</label
        >
        <textarea
          v-model="form.notes"
          rows="4"
          class="w-full input resize-none"
          placeholder="Catatan khusus..."
        ></textarea>
      </div>
    </div>
  </div>

  <FormModalCustomer
    v-if="showCustomerModal"
    :showModal="showCustomerModal"
    :isEditing="false"
    :form="customerForm"
    @close="showCustomerModal = false"
    @save="handleSaveCustomer"
  />

  <CatalogModal
    v-if="showCatalogModal"
    :showModal="showCatalogModal"
    @close="showCatalogModal = false">

  </CatalogModal>
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import FormModalCustomer from "@/components/customers/FormModal.vue";
import CatalogModal from "@/components/barangs/listModal.vue";

interface Customer {
  idCustomer?: string;
  name: string;
  address: string;
  phone: string;
  email?: string;
  marking_code_prfeix?: string;
}

interface Item {
  name: string;
  qty: number;
  shipping_type: string;
  length?: number;
  width?: number;
  original_price: number;
  selling_price: number;
}

interface Transaction {
  invoiceNo: string;
  date: Date;
  dueDate: Date;
  customer: Customer;
  items: Item[];
  suubtotal?: number;
  tax: number;
  discount: number;
  total?: number;
  notes: string;
}

const form = reactive<Transaction>({
  invoiceNo: "",
  date: new Date(),
  dueDate: new Date(),
  customer: {
    idCustomer: "",
    name: "",
    address: "",
    phone: "",
    email: "",
    marking_code_prfeix: "",
  },
  items: [],
  tax: 0,
  discount: 0,
  notes: "",
});


//customer data
const showCustomerModal = ref(false);
const customerForm = reactive<Customer>({
  name: "",
  address: "",
  phone: "",
  email: "",
  marking_code_prfeix: "",
});

const customers = ref<Customer[]>([]);

//catalog data
const showCatalogModal = ref(false);

onMounted(async () => {
  try {
    const response = await axios.get("/api/customers");
    customers.value = response.data;
  } catch (error) {
    console.error("Error fetching customers:", error);
    Swal.fire("Error", "Gagal memuat data pelanggan", "error");
  }

  generateQuotationNumber();
});

const generateQuotationNumber = () => {
  const date = new Date();
  const dd = String(date.getDate()).padStart(2, "0");
  const mm = String(date.getMonth() + 1).padStart(2, "0");
  const yyyy = date.getFullYear();
  const datePart = `${dd}${mm}${yyyy}`;
  const random = String(Math.floor(1 + Math.random() * 9999)).padStart(4, "0");
  form.invoiceNo = `QT-MR/${datePart}-${random}`;
};

const calculateTotal = (item: Item): number => {
  if (item.shipping_type === "laut_besar") {
    const length = item.length ?? 0;
    const width = item.width ?? 0;
    return item.qty * item.selling_price * length * width;
  } else {
    return item.qty * item.selling_price;
  }
};

const formatCurrency = (value: number): string => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(value);
};

const handleSaveCustomer = async () => {
  if (
    !customerForm.name ||
    !customerForm.email ||
    !customerForm.phone ||
    !customerForm.address
  ) {
    Swal.fire("Warning", "Lengkapi form!", "warning");
    return;
  }
  try {
    const res = await axios.post("/api/customers", customerForm);
    Swal.fire("Success", "Customer berhasil disimpan!", "success");
    showCustomerModal.value = false;
  } catch (e) {
    console.error(e);
    Swal.fire("Error", "Gagal simpan customer", "error");
  }
};

const selectCustomer = () => {
  if (!form.customer.idCustomer) {
    form.customer.name = "";
    form.customer.address = "";
    form.customer.phone = "";
    return;
  }

  const selectedCustomer = customers.value.find(
    (c: Customer) => String(c.idCustomer) === String(form.customer.idCustomer)
  );

  if (selectedCustomer) {
    form.customer.name = selectedCustomer.name;
    form.customer.address = selectedCustomer.address;
    form.customer.phone = selectedCustomer.phone;
  } else {
    form.customer.name = "";
    form.customer.address = "";
    form.customer.phone = "";
  }
};
</script>
