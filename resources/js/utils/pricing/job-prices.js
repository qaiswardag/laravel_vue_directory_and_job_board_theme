import { usePage } from "@inertiajs/vue3";

const jobPrices = [
    {
        name: "Single Job Posting",
        id: "single_job_post",
        dynamic_product: false,
        priceIdentifierStripe: "price_1ODSBgEuESfVmAWoBUMCqqTn",
        route: {
            name: "dashboard",
            parameters: [],
        },
        price: "$150",
        priceRaw: "150",
        totalPrice: "150",
        title: "Single job",
        description: "Single job post.",
        features: ["30 days", "Up to 8000 dayly visitors"],
        mostPopular: false,
    },
];

export default jobPrices;
