const lineBreakDivider = function (HTML) {
    const htmlCode = `<section><div class="relative py-24"> <div class="absolute inset-0 flex items-center" aria-hidden="true"><div class="w-full border-t border-gray-300"></div></div><div class="relative flex justify-start"></div></div></section>`;

    const component = {
        html_code: htmlCode,
        id: null,
        title: "Line Break Divider",
    };
    return component;
};

export default lineBreakDivider;
