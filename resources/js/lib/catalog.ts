export function formatIdr(value: number): string {
    if (value === 0) {
        return 'Gratis';
    }

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);
}

export function formatDuration(minutes: number): string {
    if (minutes < 60) {
        return `${minutes} menit`;
    }

    const hours = Math.floor(minutes / 60);
    const remainder = minutes % 60;

    return remainder === 0 ? `${hours} jam` : `${hours} jam ${remainder} menit`;
}

export function formatLessonDuration(seconds: number): string {
    const minutes = Math.floor(seconds / 60);

    return minutes < 1 ? '< 1 menit' : `${minutes} menit`;
}
