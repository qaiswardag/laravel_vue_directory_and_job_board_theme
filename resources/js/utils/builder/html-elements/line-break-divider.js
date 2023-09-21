const lineBreakDivider = function (HTML) {
    const htmlCode = `<section><div class="relative py-24"> <div class="absolute inset-0 flex items-center" aria-hidden="true"> <div class="w-full border-t border-gray-300"></div> </div> <div class="relative flex justify-start"> <span class="bg-white pr-3 text-base font-semibold leading-6 text-gray-900">Divider</span> </div> </div></section>`;

    const component = {
        html_code: htmlCode,
        id: null,
        title: "Line Break Divider",
    };
    return component;
};

export default lineBreakDivider;
