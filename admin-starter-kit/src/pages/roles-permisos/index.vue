import { Placeholder } from '@tiptap/extension-placeholder';
<script setup>
const data = ref([]);
const isRoleAddDialogVisible = ref(false);
const list_roles = ref([]);
const searchQuery = ref(null);

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Role', key: 'name' },
  { title: 'Fecha de registro', key: 'created_at' },
  { title: 'Permisos', key: 'permissions_pluck' },
  { title: 'Action', key: 'action' },
]

const list = async() => {
  try {
    const resp = await $api("role?search="+(searchQuery.value ? searchQuery.value : ''),{
      method:'GET',
      onResponseError({response}){
        console.log(response._data.error);
      }
    })
    console.log(resp);
    list_roles.value = resp.roles;
  } catch (error) {
    console.log(error);
  }
}

const addNewRole = (NewRole) => {
  console.log(NewRole);
  let backup = list_roles.value;
  list_roles.value = [];
  backup.unshift(NewRole);
  setTimeout(() => {
    list_roles.value = backup;
  }, 50);
}

const editItem = (item) => {

}

const deleteItem = (item) => {

}

onMounted(() => {
  list();
})
</script>

<template>
  <div>
    <VCard title="🔏 Roles y Permisos">
      <VCardText>
        <VRow class="justify-space-between">
          <VCol cols="3">
            <VTextField 
              Placeholder="Search Role"
              density="compact"
              class="me-3"
              v-model="searchQuery"
              @keyup.enter="list"
            />
          </VCol>

          <VCol cols="2" class="text-end">
            <VBtn @click="isRoleAddDialogVisible = !isRoleAddDialogVisible">
              Add Role
              <VIcon 
                end
                icon="ri-add-line"
              />
            </VBtn>
          </VCol>
        </VRow>
      </VCardText>

      <VDataTable
        :headers="headers"
        :items="list_roles"
        :items-per-page="5"
        class="text-no-wrap"
      >
        <template #item.id="{ item }">
          <span class="text-h6">{{ item.id }}</span>
        </template>

        <template #item.permissions_pluck="{item}">
          <ul>
            <li v-for="(permission, index) in item.permissions_pluck" :key="index">
              {{ permission }}
            </li>
          </ul>
        </template>

        <template #item.action="{ item }">
          <div class="d-flex gap-1">
            <IconBtn
              size="small"
              @click="editItem(item)"
            >
              <VIcon icon="ri-pencil-line" />
            </IconBtn>
            <IconBtn
              size="small"
              @click="deleteItem(item)"
            >
              <VIcon icon="ri-delete-bin-line" />
            </IconBtn>
          </div>
        </template>
      </VDataTable>
    </VCard>
    <RoleAddDialog v-model:isDialogVisible="isRoleAddDialogVisible" @addRole="addNewRole"></RoleAddDialog>
  </div>
</template>
