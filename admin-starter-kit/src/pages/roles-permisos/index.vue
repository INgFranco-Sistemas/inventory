import { Placeholder } from '@tiptap/extension-placeholder';
<script setup>
const data = ref([]);
const isRoleAddDialogVisible = ref(false);

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Role', key: 'name' },
  { title: 'Fecha de registro', key: 'created_at' },
  { title: 'Permisos', key: 'list_permissions' },
  { title: 'Action', key: 'action' },
]
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
        :items="data"
        :items-per-page="5"
        class="text-no-wrap"
      >
        <template #item.id="{ item }">
          <span class="text-h6">{{ item.id }}</span>
        </template>
      </VDataTable>
    </VCard>
    <RoleAddDialog v-model:isDialogVisible="isRoleAddDialogVisible"></RoleAddDialog>
  </div>
</template>
