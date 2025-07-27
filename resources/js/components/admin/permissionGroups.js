export default function permissionGroup(groupName, ids) {
  return {
    groupName,
    ids,
    get groupCheckboxId() {
      return `group_${this.groupName}`;
    },
    init() {
      this.syncGroupCheckbox();
    },
    toggleGroup(event) {
      const checked = event.target.checked;

      this.ids.forEach((id) => {
        const el = document.getElementById(`perm_${id}`);
        if (el && !el.disabled) {
          el.checked = checked;
        }
      });
    },
    syncGroupCheckbox() {
      const groupCheckbox = document.getElementById(this.groupCheckboxId);
      if (!groupCheckbox) return;

      const allChecked = this.ids.every((id) => {
        const el = document.getElementById(`perm_${id}`);
        return el && el.checked;
      });

      const noneChecked = this.ids.every((id) => {
        const el = document.getElementById(`perm_${id}`);
        return el && !el.checked;
      });

      if (allChecked) {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = true;
      } else if (noneChecked) {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = false;
      } else {
        groupCheckbox.indeterminate = true;
      }
    },
    onPermissionChange() {
      this.syncGroupCheckbox();
    },
  };
}
