import { usePage } from "@inertiajs/vue3";

const functionHelpers = {
    install(app) {
      app.config.globalProperties.$isRole = function (requiredRole) {
        const { role } = usePage().props.auth; 
        return role.name === requiredRole;
      };
      app.config.globalProperties.$isPermission = function (prvidePermissions) {
        const { permissions } = usePage().props.auth;
        const userPermissionNames = permissions.map(permission => permission.name);
        return prvidePermissions.some(name => userPermissionNames.includes(name));
      };
    }
  };
  
  export default functionHelpers;
  