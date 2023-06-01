<template>
  <div class="d-flex flex-column justify-content-start align-items-center mt-2 ">
    <app-table class="w-100" id="department-table" :options="options" @action="triggerActions" />
    <!-- <table class="table">
      <thead>
        <tr>
          <th scope="col">STT</th>
          <th scope="col">Nhân viên</th>
          <th scope="col">Thời gian (phút)</th>
          <th scope="col">Phòng ban</th>
          <th scope="col">Chi nhánh</th>
          <th scope="col">Chức vụ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>1</td>
          <td>Dev</td>
          <td>Hà nội</td>
          <td>Nhân viên</td>
        </tr>
      </tbody>
    </table> -->
  </div>
</template>

<script>
import ListLateMixin from '../../../Mixins/ListLateMixin';
export default {
  name: "ListBusinessTravel",
  mixins: [ListLateMixin],
  data() {
    return {
      department: '',
      departmentId: '',
      departments: [],
      selectedUrl: '',
      modalClass: '',
      promptIcon: '',
      promptSubtitle: '',
      departmentName: '',
      departmentUsers: '',
      isDepartmentModalActive: false,
      confirmationModalActive: false,
      isEmployeeMovementModalActive: false,
    }
  },
  methods: {
    triggerActions(row, action, active) {
      this.departmentId = row.id;
      this.promptIcon = action.icon;
      this.modalClass = action.modalClass;
      this.promptSubtitle = action.promptSubtitle;

      if (action.name === 'edit') {
        this.selectedUrl = `${action.url}/${row.id}`;
        this.isDepartmentModalActive = true;
      } else if (['deactivate', 'activate'].includes(action.key)) {
        this.confirmationModalActive = true;
      } else if (action.name === 'move-employee') {
        this.departmentId = row.id;
        this.departmentName = row.name;
        this.isEmployeeMovementModalActive = true;
      } else {
        this.getAction(row, action, active)
      }
    }
  }
}
</script>