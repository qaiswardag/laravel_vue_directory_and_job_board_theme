import { usePage } from "@inertiajs/vue3";

const storeSubscriptionPrices = function (user) {
    return [
        {
            name: "Single Store",
            id: "single_store",
            priceProductIdentifierStripe: "price_1O167uEuESfVmAWohyZDo7Ck",
            route: {
                name: "stripe.stores.create.subscription",
                parameters: null,
            },
            price: "$20",
            frequencies: "monthly",
            billed: "Billed every month",
            title: "&nbsp;",
            description:
                "The essentials to provide your best work for clients.",
            features: [
                "5 products",
                "Up to 1,000 subscribers",
                "Basic analytics",
            ],
            mostPopular: true,
        },
        {
            name: "Up to 3 Stores",
            id: "three_stores",
            priceProductIdentifierStripe: "price_1O161pEuESfVmAWoCs69v0gU",
            route: {
                name: "stripe.stores.create.subscription",
                parameters: null,
            },
            price: "$50",
            frequencies: "monthly",
            billed: "Billed every month",
            title: "Most popular",
            description:
                "The essentials to provide your best work for clients.",
            features: [
                "5 products",
                "Up to 1,000 subscribers",
                "Basic analytics",
                "48-hour support response time",
            ],
            mostPopular: false,
        },
        {
            name: "Up to 10 Stores",
            id: "ten_stores",
            priceProductIdentifierStripe: "price_1O161pEuESfVmAWoCs69v0gU",
            route: {
                name: "stripe.stores.create.subscription",
                parameters: [],
            },
            price: "$100",
            frequencies: "monthly",
            billed: "Billed every month",
            title: "&nbsp;",
            description:
                "A plan that scales with your rapidly growing business.",
            features: [
                "25 products",
                "Up to 10,000 subscribers",
                "Advanced analytics",
                "24-hour support response time",
                "Marketing automations",
            ],
            mostPopular: false,
        },
        {
            name: "Up to 200 Stores",
            id: "two_hundred_stores",
            id: "three_stores",
            priceProductIdentifierStripe: "price_1O161pEuESfVmAWoCs69v0gU",
            route: {
                name: "stripe.stores.create.subscription",
                parameters: null,
            },
            price: "$1500",
            frequencies: "monthly",
            billed: "Billed every month",
            title: "Entreprice",
            description:
                "Dedicated support and infrastructure for your company.",
            features: [
                "Unlimited products",
                "Unlimited subscribers",
                "Advanced analytics",
                "1-hour, dedicated support response time",
                "Marketing automations",
                "Custom reporting tools",
            ],
            mostPopular: false,
        },
    ];
};

export default storeSubscriptionPrices;
