<template>
  <div>
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h2 class="text-xl">Customers</h2>
      <div
        class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto"
      >
        <div class="flex gap-3">
          <div>
            <button
              type="button"
              class="btn btn-primary"
              @click="editCustomer()"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 ltr:mr-2 rtl:ml-2"
              >
                <circle
                  cx="10"
                  cy="6"
                  r="4"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  opacity="0.5"
                  d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>

              Add Customer
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'list' }"
              @click="displayType = 'list'"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
              >
                <path
                  d="M2 5.5L3.21429 7L7.5 3"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  opacity="0.5"
                  d="M2 12.5L3.21429 14L7.5 10"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M2 19.5L3.21429 21L7.5 17"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M22 19L12 19"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M22 12L12 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M22 5L12 5"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'grid' }"
              @click="displayType = 'grid'"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
              >
                <path
                  opacity="0.5"
                  d="M2.5 6.5C2.5 4.61438 2.5 3.67157 3.08579 3.08579C3.67157 2.5 4.61438 2.5 6.5 2.5C8.38562 2.5 9.32843 2.5 9.91421 3.08579C10.5 3.67157 10.5 4.61438 10.5 6.5C10.5 8.38562 10.5 9.32843 9.91421 9.91421C9.32843 10.5 8.38562 10.5 6.5 10.5C4.61438 10.5 3.67157 10.5 3.08579 9.91421C2.5 9.32843 2.5 8.38562 2.5 6.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  opacity="0.5"
                  d="M13.5 17.5C13.5 15.6144 13.5 14.6716 14.0858 14.0858C14.6716 13.5 15.6144 13.5 17.5 13.5C19.3856 13.5 20.3284 13.5 20.9142 14.0858C21.5 14.6716 21.5 15.6144 21.5 17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M2.5 17.5C2.5 15.6144 2.5 14.6716 3.08579 14.0858C3.67157 13.5 4.61438 13.5 6.5 13.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5C10.5 19.3856 10.5 20.3284 9.91421 20.9142C9.32843 21.5 8.38562 21.5 6.5 21.5C4.61438 21.5 3.67157 21.5 3.08579 20.9142C2.5 20.3284 2.5 19.3856 2.5 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M13.5 6.5C13.5 4.61438 13.5 3.67157 14.0858 3.08579C14.6716 2.5 15.6144 2.5 17.5 2.5C19.3856 2.5 20.3284 2.5 20.9142 3.08579C21.5 3.67157 21.5 4.61438 21.5 6.5C21.5 8.38562 21.5 9.32843 20.9142 9.91421C20.3284 10.5 19.3856 10.5 17.5 10.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
              </svg>
            </button>
          </div>
        </div>

        <div class="relative">
          <input
            type="text"
            placeholder="Search Customers"
            class="form-input py-2 ltr:pr-11 rtl:pl-11 peer"
            v-model="searchCustomer"
            @keyup="searchCustomers"
          />
          <div
            class="absolute ltr:right-[11px] rtl:left-[11px] top-1/2 -translate-y-1/2 peer-focus:text-primary"
          >
            <svg
              class="mx-auto"
              width="16"
              height="16"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <circle
                cx="11.5"
                cy="11.5"
                r="9.5"
                stroke="currentColor"
                stroke-width="1.5"
                opacity="0.5"
              ></circle>
              <path
                d="M18.5 18.5L22 22"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
            </svg>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-5 panel p-4 border-0 overflow-hidden">
      <template v-if="displayType === 'list'">
        <div class="datatable">
          <Vue3Datatable
            :columns="columns"
            :rows="customerList"
            :loading="loading"
            :per-page="perPage"
            :total="total"
            :page="page"
            @update:search="onSearch"
            @update:page="onPageChange"
            skin="whitespace-nowrap bh-table-hover"
            firstArrow='<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>'
            lastArrow='<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg> '
            previousArrow='<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>'
            nextArrow='<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>'
          >
            <template #serial="{ index }">
              {{ (page - 1) * perPage + index + 1 }}
            </template>
            <template #name="data">
              <div class="flex items-center gap-2">
                <img
                  :src="
                    data.value.image
                      ? `/assets/images/${data.value.image}`
                      : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                          data.value.name
                        )}&background=random&color=fff`
                  "
                  class="w-9 h-9 rounded-full max-w-none"
                  alt="user-profile"
                />
                <div class="font-semibold">
                  {{ data.value.name }}
                </div>
              </div>
            </template>
            <template #email="data">
              <a
                :href="`mailto:${data.value.email}`"
                class="text-primary hover:underline"
                >{{ data.value.email }}</a
              >
            </template>
            <template #id="data">
              <div class="flex gap-2">
                <button
                  type="button"
                  class="btn btn-outline-primary w-1/2"
                  @click="editCustomer(data.value)"
                >
                  Edit
                </button>
                <button
                  type="button"
                  class="btn btn-outline-danger w-1/2"
                  @click="deleteCustomer(data.value)"
                >
                  Delete
                </button>
              </div>
            </template>
          </Vue3Datatable>
        </div>
      </template>
    </div>
    <template v-if="displayType === 'grid'">
      <div
        class="grid 2xl:grid-cols-4 xl:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6 w-full"
      >
        <template v-for="cusomer in customerList" :key="cusomer.id">
          <div
            class="bg-white dark:bg-[#1c232f] rounded-md overflow-hidden text-center shadow relative"
          >
            <div class="bg-white/40 rounded-t-md bg-center bg-cover p-6">
              <div class="flex justify-center">
                <img
                  :src="
                    cusomer.image
                      ? `/assets/images/${cusomer.image}`
                      : '/assets/images/profile-35.png'
                  "
                  alt="Customer Profile"
                  class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                />
              </div>
            </div>
            <div class="px-6 pb-24 relative">
              <div
                class="shadow-md bg-white dark:bg-gray-900 rounded-md px-4 py-4 -mt-10"
              >
                <div class="text-xl font-semibold mb-2">{{ cusomer.name }}</div>
                <div class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                  {{ cusomer.email }}
                </div>
              </div>
              <div
                class="mt-6 grid grid-cols-1 gap-4 ltr:text-left rtl:text-right"
              >
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Email :</div>
                  <div class="truncate text-white-dark">
                    {{ cusomer.email }}
                  </div>
                </div>
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Phone :</div>
                  <div class="text-white-dark">{{ cusomer.phone }}</div>
                </div>
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Address :</div>
                  <div class="text-white-dark">{{ cusomer.address }}</div>
                </div>
              </div>
            </div>
            <div
              class="mt-6 flex gap-4 absolute bottom-0 w-full ltr:left-0 rtl:right-0 p-6"
            >
              <button
                type="button"
                class="btn btn-outline-primary w-1/2"
                @click="editCustomer(cusomer)"
              >
                Edit
              </button>
              <button
                type="button"
                class="btn btn-outline-danger w-1/2"
                @click="deleteCustomer(cusomer)"
              >
                Delete
              </button>
            </div>
          </div>
        </template>
      </div>
    </template>

    <!-- add cusomer modal -->
    <TransitionRoot appear :show="addCustomerModal" as="template">
      <Dialog
        as="div"
        @close="addCustomerModal = false"
        class="relative z-[51]"
      >
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark"
              >
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="addCustomerModal = false"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                  >
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                >
                  {{ params.id ? "Edit Customer" : "Add Customer" }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveCustomer">
                    <div class="mb-5">
                      <label for="name">Name</label>
                      <input
                        id="name"
                        type="text"
                        placeholder="Enter Name"
                        class="form-input"
                        v-model="params.name"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="email">Email</label>
                      <input
                        id="email"
                        type="email"
                        placeholder="Enter Email"
                        class="form-input"
                        v-model="params.email"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="number">Phone Number</label>
                      <input
                        id="number"
                        type="text"
                        placeholder="Enter Phone Number"
                        class="form-input"
                        v-model="params.phone"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="image">Profile Image</label>
                      <input
                        id="image"
                        type="file"
                        accept="image/*"
                        class="form-input"
                        @change="handleImageUpload"
                      />
                      <div v-if="params.image" class="mt-2">
                        <img
                          :src="
                            imagePreview || `/assets/images/${params.image}`
                          "
                          alt="Preview"
                          class="w-20 h-20 object-cover rounded-full"
                        />
                      </div>
                    </div>
                    <div class="mb-5">
                      <label for="address">Address</label>
                      <textarea
                        id="address"
                        rows="3"
                        placeholder="Enter Address"
                        class="form-textarea resize-none min-h-[130px]"
                        v-model="params.address"
                      ></textarea>
                    </div>
                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        @click="addCustomerModal = false"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        class="btn btn-primary ltr:ml-4 rtl:mr-4"
                      >
                        {{ params.id ? "Update" : "Add" }}
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>
<script lang="ts" setup>
import { ref, onMounted, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import Swal from "sweetalert2";
import { useMeta } from "@/composables/use-meta";
import Vue3Datatable from "@bhplugin/vue3-datatable";
import "@bhplugin/vue3-datatable/dist/style.css";
useMeta({ title: "Customers" });

const defaultParams = ref({
  id: null,
  name: "",
  email: "",
  phone: "",
  location: "", // Used as address in API
  image: "profile-35.png",
});
const displayType = ref("list");
const addCustomerModal = ref(false);
const params = ref(JSON.parse(JSON.stringify(defaultParams.value)));
const searchCustomer = ref("");
const imagePreview = ref("");
const columns = [
  {
    title: "No",
    field: "__slot:serial",
    sort: false,
  },
  {
    title: "Name",
    field: "name",
    sort: true,
  },
  {
    title: "Email",
    field: "email",
    sort: true,
  },
  {
    title: "Phone",
    field: "phone",
    sort: false,
  },
  {
    title: "Address",
    field: "address",
    sort: false,
  },
  {
    title: "Action",
    field: "id",
    sort: false,
  },
];

const loading = ref(false);
const total = ref(0);
const perPage = ref(10);
const page = ref(1);
const customerList = ref([]);

const fetchData = async () => {
  loading.value = true;
  try {
    const res = await fetch(`/api/customers?page=${page.value}`);
    const result = await res.json();

    // Jika API return dengan key "success"
    if (result.success) {
      customerList.value = result.data.data;
      total.value = result.data.total ?? 0;
    } else {
      showMessage("Failed to fetch customers", "error");
    }
  } catch (err) {
    console.error("Failed to fetch customers:", err);
    showMessage("Failed to fetch customers", "error");
  } finally {
    loading.value = false;
  }
};

// Initial fetch
fetchData();

// Handle page change
function onPageChange(val: number) {
  page.value = val;
  fetchData();
}

// Handle search - only search in name and email
function onSearch(val: string) {
  if (!val) {
    customerList.value = rows.value;
    return;
  }

  const searchTerm = String(val).toLowerCase();
  customerList.value = rows.value.filter((item) => {
    const name = String(item.name || "").toLowerCase();
    const email = String(item.email || "").toLowerCase();
    return name.includes(searchTerm) || email.includes(searchTerm);
  });
}

const editCustomer = (customer: any = null) => {
  console.log(customer)
  params.value = JSON.parse(JSON.stringify(defaultParams.value));
  if (customer) {
    console.log(customer)
    params.value = JSON.parse(JSON.stringify(customer));
  }

  addCustomerModal.value = true;
};

const saveCustomer = async () => {
  if (!params.value.name) {
    showMessage("Name is required.", "error");
    return true;
  }
  if (!params.value.email) {
    showMessage("Email is required.", "error");
    return true;
  }
  if (!params.value.phone) {
    showMessage("Phone is required.", "error");
    return true;
  }

  try {
    // Show confirmation dialog
    const confirmResult = await Swal.fire({
      title: params.value.id ? "Update Customer?" : "Add Customer?",
      text: params.value.id
        ? "Do you want to update this customer?"
        : "Do you want to add this customer?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: params.value.id ? "Yes, update it!" : "Yes, add it!",
      cancelButtonText: "No, cancel!",
      padding: "2em",
    });

    if (!confirmResult.isConfirmed) {
      return;
    }
    loading.value = true;
    const formData = {
      name: params.value.name,
      email: params.value.email,
      phone: params.value.phone,
      address: params.value.address,
      image: params.value.image || "profile-35.png",
    };

    let response;
    if (params.value.id) {
      // Update customer
      response = await fetch(`/api/customers/${params.value.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      });
    } else {
      // Add customer
      response = await fetch("/api/customers", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      });
    }

    if (!response.ok) {
      throw new Error("Failed to save customer");
    }

    const result = await response.json();
    if (result.success) {
      showMessage(result.message || "Customer has been saved successfully.");
      addCustomerModal.value = false;
      fetchData(); // Refresh the list
    } else {
      throw new Error(result.message || "Failed to save customer");
    }
  } catch (error) {
    console.error("Error saving customer:", error);
    showMessage(error.message || "Error saving customer", "error");
  } finally {
    loading.value = false;
  }
};

const deleteCustomer = async (customer: any = null) => {
  try {
    const result = await Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel!",
      padding: "2em",
    });

    if (result.isConfirmed) {
      loading.value = true;
      const response = await fetch(`/api/customers/${customer.id}`, {
        method: "DELETE",
      });

      if (!response.ok) {
        throw new Error("Failed to delete customer");
      }

      const result = await response.json();
      if (result.success) {
        showMessage(
          result.message || "Customer has been deleted successfully."
        );
        fetchData(); // Refresh the list
      } else {
        throw new Error(result.message || "Failed to delete customer");
      }
    }
  } catch (error) {
    console.error("Error deleting customer:", error);
    showMessage(error.message || "Error deleting customer", "error");
  } finally {
    loading.value = false;
  }
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      params.value.image = e.target.result;
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const showMessage = (msg = "", type = "success") => {
  const toast: any = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: "toast" },
  });
  toast.fire({
    icon: type,
    title: msg,
    padding: "10px 20px",
  });
};
</script>
