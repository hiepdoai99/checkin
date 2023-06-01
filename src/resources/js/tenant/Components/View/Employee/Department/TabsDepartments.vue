<template>
  <div class="content-wrapper bg-white">
    <app-page-top-section :title="$t('departments')" icon="briefcase" />
    <app-tab type="horizontal" :tabs="tabsDepartment" />
    
    
  </div>
</template>

<script>
export default {
  name: "Department",
  data() {
    return {
      tabsDepartment: [
        {
          name: this.$t('org_structure'),
          title: this.$t('org_structure'),
          component: "app-organization-structure",
          permission: true,
        },
        {
          name: this.$t('departments'),
          title: this.$t('departments'),
          component: "app-departments",
          permission: true,
        },
        {
          name: this.$t('designation'),
          title: this.$t('designation'),
          component: "app-designations",
          permission: true,
        },
        ]
    }
  }    
}
</script>