export default function formatCount(count) {
    if (count >= 1000000) {
        const roundedCount = Math.floor(count / 100000) / 10;
        return `${roundedCount}M`;
    } else if (count >= 1000) {
        const roundedCount = Math.floor(count / 100) / 10;
        return `${roundedCount}K`;
    }
    return count.toString();
}