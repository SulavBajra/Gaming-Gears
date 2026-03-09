import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

type Flash = {
    success?: string;
    error?: string;
    info?: string;
    warning?: string;
};

export function useFlashToast() {
    const page = usePage();

    watch(
        () => page.props.flash as Flash,
        (flash) => {
            if (!flash) return;
            if (flash.success) toast.success(flash.success);
            if (flash.error) toast.error(flash.error);
            if (flash.info) toast.info(flash.info);
            if (flash.warning) toast.warning(flash.warning);
        },
        { immediate: true },
    );
}
