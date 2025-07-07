export function htmlSectionsToComponentArray(htmlString) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(htmlString, "text/html");
    const sections = doc.querySelectorAll("section");
    return Array.from(sections).map((section) => ({
        html_code: section.outerHTML,
        id: section.getAttribute("data-componentid") || null,
        title:
            section.getAttribute("data-component-title") ||
            section.getAttribute("title") ||
            "Untitled Component",
    }));
}
