export default function permissionGroup(groupName, ids) {
  return {
    groupName,
    ids,
    get groupCheckboxId() {
      return `group_${this.groupName}`;
    },
    init() {
      this.$nextTick(() => {
        this.syncGroupCheckbox();
      });
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

      const enabledCheckboxes = this.ids
        .map((id) => document.getElementById(`perm_${id}`))
        .filter((el) => el && !el.disabled);

      if (enabledCheckboxes.length === 0) {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = false;
        return;
      }

      const allChecked = enabledCheckboxes.every((el) => el.checked);
      const noneChecked = enabledCheckboxes.every((el) => !el.checked);

      if (allChecked) {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = true;
      } else if (noneChecked) {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = false;
      } else {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = true;
      }
    },
    onPermissionChange() {
      this.syncGroupCheckbox();
    },
  };
}
