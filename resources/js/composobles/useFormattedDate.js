export function useFormattedDate() {
    const formatDate = (DateString) =>  {
        if(!DateString) return '';
        return new Date(DateString).toLocaleDateString('en:GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    };

    return { formatDate };
}
