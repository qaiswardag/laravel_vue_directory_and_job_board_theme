const storeSubscriptionPrices = [
    {
        name: "Single Store",
        id: "single_store",
        dynamic_product: false,
        priceIdentifierStripe: "price_1O167uEuESfVmAWohyZDo7Ck",
        route: {
            name: "stripe.stores.create.subscription",
            parameters: null,
        },
        price: "$25",
        frequencies: "per store / monthly",
        billed: "Billed every month",
        title: "Single store",
        description: "Affordable & with all features.",
        features: ["5 products", "Up to 1,000 subscribers", "Basic analytics"],
        mostPopular: true,
    },
    {
        name: "Up to 3 Stores",
        id: "three_stores",
        dynamic_product: false,
        priceIdentifierStripe: "price_1O161pEuESfVmAWoCs69v0gU",
        route: {
            name: "stripe.stores.create.subscription",
            parameters: null,
        },
        price: "$20",
        frequencies: "per store / monthly",
        billed: "Billed every month",
        title: "Most popular",
        description: "Affordable & with all features.",
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
        dynamic_product: true,
        priceIdentifierStripe: "price_1O161pEuESfVmAWoCs69v0gU",
        route: {
            name: "stripe.stores.create.subscription",
            parameters: [],
        },
        price: "$15",
        frequencies: "per store / monthly",
        billed: "Billed every month",
        title: "Entreprice",
        description: "A plan that scales with your business.",
        features: [
            "25 products",
            "Up to 10,000 subscribers",
            "Advanced analytics",
            "24-hour support response time",
            "Marketing automations",
        ],
        mostPopular: false,
    },
];

export default storeSubscriptionPrices;
