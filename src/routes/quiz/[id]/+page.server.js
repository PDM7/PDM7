export const prerender = true;

export const entries = () => {
    return [
        { id: "550e8400-e29b-41d4-a716-446655440000" },
        { id: "f47ac10b-58cc-4372-a567-0e02b2c3d479" }
    ];
};

export function load({ params }) {
    return {
        id: params.id
    };
}