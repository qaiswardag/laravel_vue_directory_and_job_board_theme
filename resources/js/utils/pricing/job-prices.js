import { usePage } from "@inertiajs/vue3";

const storeSubscriptionPrices = function (user) {
    return [
        {
            name: "Single Job Posting",
            id: "single_job_posting",
            priceProductIdentifierStripe: "price_1O167uEuESfVmAWohyZDo7Ck",
            price: "$200",
            title: "&nbsp;",
            description: "Single job post.",
            features: [
                "30 days",
                "Up to 8000 dayly visitors",
                "Basic analytics",
                "Only for Fashion Industry",
                "Responsive & Mobile-Friendly",
                "Be Where Your Customers Are",
                "Featured in Newsletter",
            ],
            mostPopular: false,
        },
    ];
};

export default storeSubscriptionPrices;
