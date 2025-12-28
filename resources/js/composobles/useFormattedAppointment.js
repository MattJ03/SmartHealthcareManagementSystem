import { computed } from "vue";
import {n} from "vue-router/dist/devtools-EWN81iOl.mjs";

export function useFormattedAppointment(appointmentRef) {
    const appointmentDate = computed(() =>  {
       if(!appointmentRef.value?.starts_at) return '';
       return new Date(appointmentRef.value.starts_at).toLocaleString('en-GB', {
           weekday: "long",
           day: "numeric",
           month: "long",
           year: "numeric",
       });
    });

    const appointmentTime = computed(() => {
       if(!appointmentRef.value?.starts_at) return '';
       return new Date(appointmentRef.value.starts_at).toLocaleString('en-GB', {
           hour: "numeric",
           minute: "numeric",
       });
    });

    return { appointmentDate, appointmentTime };
}
