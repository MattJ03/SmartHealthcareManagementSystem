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

    const appointmentPeriod = computed(() => {
        if(!appointmentRef.value?.starts_at) return '';
        const date = new Date(appointmentRef.value.starts_at);
        return date.toLocaleString('en-GB', { hour: '2-digit', hour12: true}).toUpperCase().split(' ')[1];
    });

    return { appointmentDate, appointmentTime, appointmentPeriod };
}
