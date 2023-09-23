import tailwindColors from "@/utils/builder/tailwaind-colors";
import tailwindOpacities from "@/utils/builder/tailwind-opacities";
import tailwindFontSizes from "@/utils/builder/tailwind-font-sizes";
import tailwindFontStyles from "@/utils/builder/tailwind-font-styles";
import compiledCSS from "@/utils/builder/compiledCSS";
import tailwindPaddingAndMargin from "@/utils/builder/tailwind-padding-margin";
import tailwindBorderRadius from "@/utils/builder/tailwind-border-radius";
import tailwindBorderStyleWidthPlusColor from "@/utils/builder/tailwind-border-style-width-color";
import { computed, ref, nextTick } from "vue";
import { v4 as uuidv4 } from "uuid";

class PageBuilder {
    constructor(store) {
        /**
         * Initialize an instance variable 'elementsWithListeners' as a WeakSet.
         *
         * A WeakSet is a special type of Set that holds weak references to its elements,
         * meaning that an element could be garbage collected if there is no other reference to it.
         * This is especially useful in the context of managing DOM elements and their associated events,
         * as it allows for efficient and automated cleanup of references to DOM elements that have been removed.
         *
         * By checking if an element is in this WeakSet before attaching an event listener,
         * we can prevent redundant addition of the same event listener to an element.
         * This helps in managing the memory usage and performance of the application.
         */
        this.elementsWithListeners = new WeakSet();

        this.nextTick = nextTick();

        this.timer = null;
        this.backgroundColors = tailwindColors.backgroundColors();
        this.textColors = tailwindColors.textColors();
        this.store = store;
        this.getTextAreaVueModel = computed(
            () => this.store.getters["pageBuilderState/getTextAreaVueModel"]
        );
        this.getLocalStorageItemName = computed(
            () => this.store.getters["pageBuilderState/getLocalStorageItemName"]
        );

        this.getCurrentImage = computed(
            () => this.store.getters["mediaLibrary/getCurrentImage"]
        );
        this.getHyberlinkEnable = computed(
            () => this.store.getters["pageBuilderState/getHyberlinkEnable"]
        );
        this.getElement = computed(
            () => this.store.getters["pageBuilderState/getElement"]
        );

        this.getComponents = computed(
            () => this.store.getters["pageBuilderState/getComponents"]
        );
        this.getComponent = computed(
            () => this.store.getters["pageBuilderState/getComponent"]
        );
        this.getNextSibling = computed(
            () => this.store.getters["pageBuilderState/getNextSibling"]
        );
        this.getParentElement = computed(
            () => this.store.getters["pageBuilderState/getParentElement"]
        );
        this.getRestoredElement = computed(
            () => this.store.getters["pageBuilderState/getRestoredElement"]
        );
    }

    shouldRunMethods() {
        if (!this.getComponents.value) {
            console.error("Components have a falsy value.");
            return false;
        }

        if (!this.getComponent.value) {
            console.error("Component have a falsy value.");
            return false;
        }
        if (!this.getElement.value) {
            console.error("Element have a falsy value.");
            return false;
        }

        return true;
    }

    /**
     * The function is adding mouseover
     * and click event listeners to a specific DOM element
     *
     */
    addElementsListeners = (element) => {
        console.log("addElementsListeners");

        // Only run on mouse over
        element.addEventListener("mouseover", (e) => {
            e.stopPropagation();

            if (document.querySelector("[hovered]") !== null) {
                document.querySelector("[hovered]").removeAttribute("hovered");
            }

            element.setAttribute("hovered", "");
        });

        // Only run on mouse leave
        element.addEventListener("mouseleave", (e) => {
            e.stopPropagation();

            if (document.querySelector("[hovered]") !== null) {
                document.querySelector("[hovered]").removeAttribute("hovered");
            }
        });

        // Only run during on mouse click
        element.addEventListener("click", (e) => {
            this.store.commit("pageBuilderState/setMenuRight", true);

            e.preventDefault();
            e.stopPropagation();

            if (document.querySelector("[selected]") !== null) {
                document
                    .querySelector("[selected]")
                    .removeAttribute("selected");
            }

            e.currentTarget.removeAttribute("hovered");

            e.currentTarget.setAttribute("selected", "");

            console.log("commit me old:", e.currentTarget);

            this.store.commit("pageBuilderState/setElement", e.currentTarget);

            this.handlePageBuilderMethods();
        });
    };

    /**
     * The function is used to
     * attach event listeners to each element within a 'section'
     *
     */
    setEventListenersForElements = () => {
        console.log("setEventListenersForElements");
        document.querySelectorAll("section *").forEach((element) => {
            // Check if the element is not one of the excluded tags
            if (
                !["P", "H1", "H2", "H3", "H4", "H5", "H6"].includes(
                    element.tagName
                )
            ) {
                if (
                    this.elementsWithListeners &&
                    !this.elementsWithListeners.has(element)
                ) {
                    this.elementsWithListeners.add(element);
                    this.addElementsListeners(element);
                }
            }
        });
    };
    //
    //
    //
    /**
     * The function is used to
     * attach event listeners to each element within a 'section'
     *
     */
    setListenersToNewComponentElements = async (componentID) => {
        console.log("setListenersToNewComponentElements");

        const section = document.querySelector(
            `section[data-componentid="${componentID}"]`
        );

        if (!section) {
            console.error("Unable to find section with ID:", componentID);
            return;
        }

        section.querySelectorAll("section *").forEach((element) => {
            //

            //
            // Check if the element is not one of the excluded tags
            if (
                !["P", "H1", "H2", "H3", "H4", "H5", "H6"].includes(
                    element.tagName
                )
            ) {
                if (
                    this.elementsWithListeners &&
                    !this.elementsWithListeners.has(element)
                ) {
                    this.addElementsListeners(element);
                    this.elementsWithListeners.add(element);
                }
            }
        });
    };
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    observePlusSyncHTMLElements = async () => {
        console.log("observePlusSyncHTMLElements");
        if (!this.getComponents.value) {
            this.store.commit("pageBuilderState/setComponents", []);
        }

        if (document.querySelector("[hovered]") !== null) {
            document.querySelector("[hovered]").removeAttribute("hovered");
        }

        this.setEventListenersForElements();

        this.getComponents.value.forEach((component) => {
            const section = document.querySelector(
                `section[data-componentid="${component.id}"]`
            );

            if (section) {
                component.html_code = section.outerHTML;
            }
        });

        // Initialize the MutationObserver
        this.observer = new MutationObserver((mutationsList, observer) => {
            // Once we have seen a mutation, stop observing and resolve the promise
            observer.disconnect();
        });

        // Start observing the document with the configured parameters
        this.observer.observe(document, {
            attributes: true,
            childList: true,
            subtree: true,
        });

        // Use the MutationObserver to wait for the next DOM change
        await new Promise((resolve) => {
            resolve();
        });

        this.setEventListenersForElements();

        // This will be executed after the DOM has been updated
        // this.store.commit(
        //     "pageBuilderState/setElement",
        //     document.querySelector("[selected]")
        // );
    };

    cloneCompObjForDOMInsertion(componentObject) {
        console.log("cloneCompObjForDOMInsertion");
        // Hide slider and right menu
        this.store.commit("pageBuilderState/setMenuRight", false);

        // Deep clone clone component
        const clonedComponent = { ...componentObject };

        // Create a DOMParser instance
        const parser = new DOMParser();

        // Parse the HTML content of the clonedComponent using the DOMParser
        const doc = parser.parseFromString(
            clonedComponent.html_code,
            "text/html"
        );

        // Selects all elements within the HTML document, including elements like:
        const elements = doc.querySelectorAll("*");

        // Add the component id to the section element
        const section = doc.querySelector("section");
        if (section) {
            // Generate a unique ID using uuidv4() and assign it to the section
            section.dataset.componentid = uuidv4();
        }

        // Update the clonedComponent id with the newly generated unique ID
        clonedComponent.id = section.dataset.componentid;

        // Update the HTML content of the clonedComponent with the modified HTML
        clonedComponent.html_code = doc.querySelector("section").outerHTML;

        // return to the cloned element to be dropped
        return clonedComponent;
    }

    #modifyElementCSS(selectedCSS, CSSArray, mutationName) {
        console.log("getElement is:", this.getElement.value);
        console.log("#modifyElementCSS");
        if (!this.shouldRunMethods()) return;

        const currentCSS = CSSArray.find((CSS) => {
            return this.getElement.value.classList.contains(CSS);
        });

        // set to 'none' if undefined
        let elementClass = currentCSS || "none";

        this.store.commit(`pageBuilderState/${mutationName}`, elementClass);

        if (typeof selectedCSS === "string" && selectedCSS !== "none") {
            if (
                elementClass &&
                this.getElement.value.classList.contains(elementClass)
            ) {
                this.getElement.value.classList.remove(elementClass);
            }

            this.getElement.value.classList.add(selectedCSS);
            elementClass = selectedCSS;
        } else if (
            typeof selectedCSS === "string" &&
            selectedCSS === "none" &&
            elementClass
        ) {
            this.getElement.value.classList.remove(elementClass);
            elementClass = selectedCSS;
            // this.store.commit("pageBuilderState/setElement", this.getElement.value);
        }

        this.store.commit(`pageBuilderState/${mutationName}`, elementClass);

        return currentCSS;
    }

    removeHoveredAndSelected() {
        console.log("removeHoveredAndSelected");
        if (document.querySelector("[hovered]") !== null) {
            document.querySelector("[hovered]").removeAttribute("hovered");
        }

        if (document.querySelector("[selected]") !== null) {
            document.querySelector("[selected]").removeAttribute("selected");
        }
    }

    currentClasses() {
        console.log("currentClasses");
        if (!this.shouldRunMethods()) return;

        // convert classList to array
        let classListArray = Array.from(this.getElement.value.classList);

        // commit array to store
        this.store.commit("pageBuilderState/setCurrentClasses", classListArray);
    }

    handleAddClasses(userSelectedClass) {
        console.log("currentClasses");
        if (!this.shouldRunMethods()) return;

        if (
            typeof userSelectedClass === "string" &&
            userSelectedClass !== "" &&
            !userSelectedClass.includes(" ") &&
            // Check if class already exists
            !this.getElement.value.classList.contains(userSelectedClass)
        ) {
            this.getElement.value.classList.add(userSelectedClass);
            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
            this.store.commit("pageBuilderState/setClass", userSelectedClass);
        }
    }
    handleRemoveClasses(userSelectedClass) {
        console.log("handleRemoveClasses");
        if (!this.shouldRunMethods()) return;

        // remove selected class from element
        if (this.getElement.value.classList.contains(userSelectedClass)) {
            this.getElement.value.classList.remove(userSelectedClass);
            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
            this.store.commit(
                "pageBuilderState/removeClass",
                userSelectedClass
            );
        }
    }

    handleDeleteElement() {
        console.log("handleDeleteElement");
        if (!this.shouldRunMethods()) return;

        // Get the element to be deleted
        const element = this.getElement.value;

        // Store the parent of the deleted element
        this.store.commit(
            "pageBuilderState/setParentElement",
            element.parentNode
        );
        // Store the outerHTML of the deleted element
        this.store.commit(
            "pageBuilderState/setRestoredElement",
            element.outerHTML
        );
        // Store the next sibling of the deleted element
        this.store.commit(
            "pageBuilderState/setNextSibling",
            element.nextSibling
        );

        this.store.commit("pageBuilderState/setComponent", null);

        // Remove the element from the DOM
        element.remove();
    }
    handleRestoreElement() {
        console.log("handleRestoreElement");
        if (!this.shouldRunMethods()) return;

        // Get the stored deleted element and its parent
        if (
            this.getRestoredElement.value !== null &&
            this.getParentElement.value !== null
        ) {
            // Create a new element from the stored outerHTML
            const newElement = document.createElement("div");
            newElement.innerHTML = this.getRestoredElement.value;

            // Append the restored element to its parent
            // Insert the restored element before its next sibling in its parent
            this.getParentElement.value.insertBefore(
                newElement.firstChild,
                this.getNextSibling.value
            );
        }

        // Clear
        this.store.commit("pageBuilderState/setParentElement", null);
        this.store.commit("pageBuilderState/setRestoredElement", null);
        this.store.commit("pageBuilderState/setComponent", null);
    }

    handleFontWeight(userSelectedFontWeight) {
        console.log("handleFontWeight");
        this.#modifyElementCSS(
            userSelectedFontWeight,
            tailwindFontStyles.fontWeight,
            "setFontWeight"
        );
    }
    handleFontFamily(userSelectedFontFamily) {
        console.log("handleFontFamily");
        this.#modifyElementCSS(
            userSelectedFontFamily,
            tailwindFontStyles.fontFamily,
            "setFontFamily"
        );
    }
    handleFontStyle(userSelectedFontStyle) {
        console.log("handleFontStyle");
        this.#modifyElementCSS(
            userSelectedFontStyle,
            tailwindFontStyles.fontStyle,
            "setFontStyle"
        );
    }
    handleVerticalPadding(userSelectedVerticalPadding) {
        console.log("handleVerticalPadding");
        this.#modifyElementCSS(
            userSelectedVerticalPadding,
            tailwindPaddingAndMargin.verticalPadding,
            "setFontVerticalPadding"
        );
    }
    handleHorizontalPadding(userSelectedHorizontalPadding) {
        console.log("handleHorizontalPadding");
        this.#modifyElementCSS(
            userSelectedHorizontalPadding,
            tailwindPaddingAndMargin.horizontalPadding,
            "setFontHorizontalPadding"
        );
    }

    handleVerticalMargin(userSelectedVerticalMargin) {
        console.log("handleVerticalMargin");
        this.#modifyElementCSS(
            userSelectedVerticalMargin,
            tailwindPaddingAndMargin.verticalMargin,
            "setFontVerticalMargin"
        );
    }
    handleHorizontalMargin(userSelectedHorizontalMargin) {
        console.log("handleHorizontalMargin");
        this.#modifyElementCSS(
            userSelectedHorizontalMargin,
            tailwindPaddingAndMargin.horizontalMargin,
            "setFontHorizontalMargin"
        );
    }

    // border style & width / start
    handleBorderStyle(borderStyle) {
        console.log("handleBorderStyle");
        this.#modifyElementCSS(
            borderStyle,
            tailwindBorderStyleWidthPlusColor.borderStyle,
            "setBorderStyle"
        );
    }
    handleBorderWidth(borderWidth) {
        console.log("handleBorderWidth");
        this.#modifyElementCSS(
            borderWidth,
            tailwindBorderStyleWidthPlusColor.borderWidth,
            "setBorderWidth"
        );
    }
    handleBorderColor(borderColor) {
        console.log("handleBorderColor");
        this.#modifyElementCSS(
            borderColor,
            tailwindBorderStyleWidthPlusColor.borderColor,
            "setBorderColor"
        );
    }
    // border style & width / end

    // border radius / start
    handleBorderRadiusGlobal(borderRadiusGlobal) {
        console.log("handleBorderRadiusGlobal");
        this.#modifyElementCSS(
            borderRadiusGlobal,
            tailwindBorderRadius.roundedGlobal,
            "setBorderRadiusGlobal"
        );
    }
    handleBorderRadiusTopLeft(borderRadiusTopLeft) {
        console.log("handleBorderRadiusTopLeft");
        this.#modifyElementCSS(
            borderRadiusTopLeft,
            tailwindBorderRadius.roundedTopLeft,
            "setBorderRadiusTopLeft"
        );
    }
    handleBorderRadiusTopRight(borderRadiusTopRight) {
        console.log("handleBorderRadiusTopRight");
        this.#modifyElementCSS(
            borderRadiusTopRight,
            tailwindBorderRadius.roundedTopRight,
            "setBorderRadiusTopRight"
        );
    }
    handleBorderRadiusBottomleft(borderRadiusBottomleft) {
        console.log("handleBorderRadiusBottomleft");
        this.#modifyElementCSS(
            borderRadiusBottomleft,
            tailwindBorderRadius.roundedBottomLeft,
            "setBorderRadiusBottomleft"
        );
    }
    handleBorderRadiusBottomRight(borderRadiusBottomRight) {
        console.log("handleBorderRadiusBottomRight");
        this.#modifyElementCSS(
            borderRadiusBottomRight,
            tailwindBorderRadius.roundedBottomRight,
            "setBorderRadiusBottomRight"
        );
    }
    // border radius / end

    handleFontSize(userSelectedFontSize) {
        console.log("handleFontSize");
        if (!this.shouldRunMethods()) return;

        let fontBase = tailwindFontSizes.fontBase.find((size) => {
            return this.getElement.value.classList.contains(size);
        });
        if (fontBase === undefined) {
            fontBase = "base:none";
        }

        let fontDesktop = tailwindFontSizes.fontDesktop.find((size) => {
            return this.getElement.value.classList.contains(size);
        });
        if (fontDesktop === undefined) {
            fontDesktop = "lg:none";
        }

        let fontTablet = tailwindFontSizes.fontTablet.find((size) => {
            return this.getElement.value.classList.contains(size);
        });
        if (fontTablet === undefined) {
            fontTablet = "md:none";
        }

        let fontMobile = tailwindFontSizes.fontMobile.find((size) => {
            return this.getElement.value.classList.contains(size);
        });
        if (fontMobile === undefined) {
            fontMobile = "sm:none";
        }

        // set fonts
        this.store.commit("pageBuilderState/setFontBase", fontBase);
        this.store.commit("pageBuilderState/setFontDesktop", fontDesktop);
        this.store.commit("pageBuilderState/setFontTablet", fontTablet);
        this.store.commit("pageBuilderState/setFontMobile", fontMobile);

        const getFontBase = computed(() => {
            return this.store.getters["pageBuilderState/getFontBase"];
        });
        const getFontDesktop = computed(() => {
            return this.store.getters["pageBuilderState/getFontDesktop"];
        });
        const getFontTablet = computed(() => {
            return this.store.getters["pageBuilderState/getFontTablet"];
        });
        const getFontMobile = computed(() => {
            return this.store.getters["pageBuilderState/getFontMobile"];
        });

        if (userSelectedFontSize !== undefined) {
            if (
                !userSelectedFontSize.includes("sm:") &&
                !userSelectedFontSize.includes("md:") &&
                !userSelectedFontSize.includes("lg:")
            ) {
                this.getElement.value.classList.remove(getFontBase.value);
                if (!userSelectedFontSize.includes("base:none")) {
                    this.getElement.value.classList.add(userSelectedFontSize);
                }

                this.store.commit(
                    "pageBuilderState/setFontBase",
                    userSelectedFontSize
                );
            }
            if (userSelectedFontSize.includes("lg:")) {
                this.getElement.value.classList.remove(getFontDesktop.value);
                if (!userSelectedFontSize.includes("lg:none")) {
                    this.getElement.value.classList.add(userSelectedFontSize);
                }

                this.store.commit(
                    "pageBuilderState/setFontDesktop",
                    userSelectedFontSize
                );
            }
            if (userSelectedFontSize.includes("md:")) {
                this.getElement.value.classList.remove(getFontTablet.value);
                if (!userSelectedFontSize.includes("md:none")) {
                    this.getElement.value.classList.add(userSelectedFontSize);
                }

                this.store.commit(
                    "pageBuilderState/setFontTablet",
                    userSelectedFontSize
                );
            }
            if (userSelectedFontSize.includes("sm:")) {
                this.getElement.value.classList.remove(getFontMobile.value);
                if (!userSelectedFontSize.includes("sm:none")) {
                    this.getElement.value.classList.add(userSelectedFontSize);
                }

                this.store.commit(
                    "pageBuilderState/setFontMobile",
                    userSelectedFontSize
                );
            }

            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
        }
    }

    handleCustomBackgroundColor(userSelectedColor, enabledCustomColor) {
        console.log("handleCustomBackgroundColor");
        if (!this.shouldRunMethods()) return;

        // if user is selecting a custom HEX color
        if (
            userSelectedColor === undefined &&
            enabledCustomColor === undefined
        ) {
            // Get the style property
            let bgColor = this.getElement.value.style.backgroundColor;

            // Check for inline background color
            if (typeof bgColor === "string" && bgColor.length !== 0) {
                this.store.commit(
                    "pageBuilderState/setEnabledCustomColorBackground",
                    true
                );
                this.store.commit(
                    "pageBuilderState/setBackgroundColorCustom",
                    bgColor
                );
            }

            // Check for inline background color
            if (typeof bgColor === "string" && bgColor.length === 0) {
                this.store.commit(
                    "pageBuilderState/setEnabledCustomColorBackground",
                    false
                );
                this.store.commit(
                    "pageBuilderState/setBackgroundColorCustom",
                    null
                );
            }
        }

        // if user is selecting a custom HEX color
        if (enabledCustomColor === true) {
            this.getElement.value.style.backgroundColor = userSelectedColor;
            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
        }
    }
    handleCustomTextColor(userSelectedColor, enabledCustomColor) {
        console.log("handleCustomTextColor");
        if (!this.shouldRunMethods()) return;

        // if user is selecting a custom HEX color
        if (
            userSelectedColor === undefined &&
            enabledCustomColor === undefined
        ) {
            // Get the style property
            let textColor = this.getElement.value.style.color;

            // Check for inline background color
            if (typeof textColor === "string" && textColor.length !== 0) {
                this.store.commit(
                    "pageBuilderState/setEnabledCustomColorText",
                    true
                );
                this.store.commit(
                    "pageBuilderState/setTextColorCustom",
                    textColor
                );
            }

            // Check for inline background color
            if (typeof textColor === "string" && textColor.length === 0) {
                this.store.commit(
                    "pageBuilderState/setEnabledCustomColorText",
                    false
                );
                this.store.commit("pageBuilderState/setTextColorCustom", null);
            }
        }

        // if user is selecting a custom HEX color
        if (enabledCustomColor === true) {
            this.getElement.value.style.color = userSelectedColor;
            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
        }
    }

    handleBackgroundColor(userSelectedColor) {
        console.log("handleBackgroundColor");
        this.#modifyElementCSS(
            userSelectedColor,
            this.backgroundColors,
            "setBackgroundColor"
        );
    }
    handleTextColor(userSelectedColor) {
        console.log("handleTextColor");
        this.#modifyElementCSS(
            userSelectedColor,
            this.textColors,
            "setTextColor"
        );
    }
    removeCustomColorBackground() {
        console.log("removeCustomColorBackground");
        if (!this.shouldRunMethods()) return;

        this.getElement.value.style.removeProperty("background-color");
        this.store.commit(
            "pageBuilderState/setEnabledCustomColorBackground",
            null
        );
        this.store.commit("pageBuilderState/setBackgroundColorCustom", null);
        // this.store.commit("pageBuilderState/setElement", this.getElement.value);
    }
    removeCustomColorText() {
        console.log("removeCustomColorText");
        if (!this.shouldRunMethods()) return;

        this.getElement.value.style.removeProperty("color");
        this.store.commit("pageBuilderState/setEnabledCustomColorText", null);
        this.store.commit("pageBuilderState/setTextColorCustom", null);
        // this.store.commit("pageBuilderState/setElement", this.getElement.value);
    }
    handleBackgroundOpacity(opacity) {
        console.log("handleBackgroundOpacity");
        this.#modifyElementCSS(
            opacity,
            tailwindOpacities.backgroundOpacities,
            "setBackgroundOpacity"
        );
    }
    handleOpacity(opacity) {
        console.log("handleOpacity");
        this.#modifyElementCSS(
            opacity,
            tailwindOpacities.opacities,
            "setOpacity"
        );
    }
    saveComponentsLocalStorage(components) {
        console.log("saveComponentsLocalStorage");
        localStorage.setItem(
            this.getLocalStorageItemName.value,
            JSON.stringify(components)
        );
    }

    deleteAllComponents() {
        console.log("deleteAllComponents");
        this.store.commit("pageBuilderState/setComponents", []);
    }

    deleteComponent() {
        console.log("deleteComponent");
        if (!this.shouldRunMethods()) return;

        // Find the index of the component to delete
        const indexToDelete = this.getComponents.value.findIndex(
            (component) => component.id === this.getComponent.value.id
        );

        if (indexToDelete === -1) {
            // Component not found in the array, handle this case as needed.
            return;
        }

        // Remove the component from the array
        this.getComponents.value.splice(indexToDelete, 1);
        this.store.commit(
            "pageBuilderState/setComponents",
            this.getComponents.value
        );
    }

    // move component
    // runs when html components are rearranged
    moveComponent(direction) {
        console.log("moveComponent");
        if (!this.shouldRunMethods()) return;

        if (this.getComponents.value.length <= 1) return;

        // Get the component you want to move (replace this with your actual logic)
        const componentToMove = this.getComponent.value;

        // Determine the new index where you want to move the component
        const currentIndex = this.getComponents.value.findIndex(
            (component) => component.id === componentToMove.id
        );

        if (currentIndex === -1) {
            // Component not found in the array, handle this case as needed.
            return;
        }

        const newIndex = currentIndex + direction;

        // Ensure the newIndex is within bounds
        if (newIndex < 0 || newIndex >= this.getComponents.value.length) {
            return;
        }

        // Move the component to the new position
        this.getComponents.value.splice(currentIndex, 1);
        this.getComponents.value.splice(newIndex, 0, componentToMove);
    }

    addSpaceForEmptyTextArea = () => {
        console.log("addSpaceForEmptyTextArea");
        if (!this.shouldRunMethods()) return;

        // text content
        if (typeof this.getElement.value.innerHTML !== "string") {
            return;
        }
        const element = this.getElement.value;
        const elementTag = element.tagName;

        if (
            ["DIV"].includes(elementTag) &&
            element.tagName.toLowerCase() !== "img" &&
            Number(element.textContent.length) === 0
        ) {
            element.classList.add("h-6");
            element.classList.add("bg-red-50");
        } else {
            element.classList.remove("h-6");
            element.classList.remove("bg-red-50");
        }
    };

    handleTextInput = async (textContentVueModel) => {
        console.log("handleTextInput");
        if (!this.shouldRunMethods()) return;

        const element = this.getElement.value;

        // text content
        if (typeof element?.innerHTML !== "string") {
            return;
        }

        if (typeof element.innerHTML === "string") {
            await this.nextTick;

            this.store.commit(
                "pageBuilderState/setTextAreaVueModel",
                element.innerHTML
            );

            this.getElement.value.innerHTML = textContentVueModel;

            // this.store.commit(
            //     "pageBuilderState/setElement",
            //     this.getElement.value
            // );
        }

        this.addSpaceForEmptyTextArea();
    };

    previewCurrentDesign() {
        console.log("previewCurrentDesign");
        const addedHtmlComponents = ref([]);
        // preview current design in external browser tab
        // iterate over each top-level section component
        document
            .querySelectorAll("section:not(section section)")
            .forEach((section) => {
                // remove hovered and selected

                // remove hovered
                if (section.querySelector("[hovered]") !== null) {
                    section
                        .querySelector("[hovered]")
                        .removeAttribute("hovered");
                }

                // remove selected
                if (section.querySelector("[selected]") !== null) {
                    section
                        .querySelector("[selected]")
                        .removeAttribute("selected");
                }

                // push outer html into the array
                addedHtmlComponents.value.push(section.outerHTML);
            });

        // stringify added html components
        addedHtmlComponents.value = JSON.stringify(addedHtmlComponents.value);

        // commit
        this.store.commit(
            "pageBuilderState/setCurrentLayoutPreview",
            addedHtmlComponents.value
        );

        // set added html components back to empty array
        addedHtmlComponents.value = [];

        //
    }

    areComponentsStoredInLocalStorage() {
        console.log("areComponentsStoredInLocalStorage");
        const savedCurrentDesign = localStorage.getItem(
            this.getLocalStorageItemName.value
        );
        if (savedCurrentDesign) {
            let components = JSON.parse(savedCurrentDesign);
            if (!components) {
                components = [];
            }

            this.store.commit("pageBuilderState/setComponents", components);
            return true;
        }

        return false;
    }
    //
    updateBasePrimaryImage() {
        console.log("updateBasePrimaryImage");
        if (this.getCurrentImage.value.currentImage?.mediaLibrary?.path) {
            this.store.commit(
                "pageBuilderState/setBasePrimaryImage",
                `/storage/uploads/${this.getCurrentImage.value.currentImage.mediaLibrary.large_path}`
            );
        }
    }
    showBasePrimaryImage() {
        console.log("showBasePrimaryImage");
        const currentImageContainer = document.createElement("div");
        currentImageContainer.innerHTML = this.getElement.value.outerHTML;

        // Get all img and div within the current image container
        const imgElements = currentImageContainer.getElementsByTagName("img");
        const divElements = currentImageContainer.getElementsByTagName("div");

        // Check if there is exactly one img and no div
        if (imgElements.length === 1 && divElements.length === 0) {
            // Return the source of the only img

            this.store.commit(
                "pageBuilderState/setBasePrimaryImage",
                imgElements[0].src
            );
            return;
        }

        this.store.commit("pageBuilderState/setBasePrimaryImage", null);
    }

    #addHyperlinkToElement(hyperlinkEnable, urlInput, openHyperlinkInNewTab) {
        console.log("#addHyperlinkToElement");
        if (!this.shouldRunMethods()) return;

        const parentHyperlink = this.getElement.value.closest("a");
        const hyperlink = this.getElement.value.querySelector("a");

        this.store.commit("pageBuilderState/setHyperlinkError", null);

        // url validation
        const urlRegex = /^https?:\/\//;

        const isValidURL = ref(true);

        if (hyperlinkEnable === true && urlInput !== null) {
            isValidURL.value = urlRegex.test(urlInput);
        }

        if (isValidURL.value === false) {
            this.store.commit("pageBuilderState/setHyperlinkMessage", null);
            this.store.commit(
                "pageBuilderState/setHyperlinkError",
                "URL is not valid"
            );
            return;
        }

        if (hyperlinkEnable === true && typeof urlInput === "string") {
            // check if element contains child hyperlink tag
            // updated existing url
            if (hyperlink !== null && urlInput.length !== 0) {
                hyperlink.href = urlInput;

                // Conditionally set the target attribute if openHyperlinkInNewTab is true
                if (openHyperlinkInNewTab === true) {
                    hyperlink.target = "_blank";
                }
                if (openHyperlinkInNewTab === false) {
                    hyperlink.removeAttribute("target");
                }

                hyperlink.textContent = this.getElement.value.textContent;
                this.store.commit(
                    "pageBuilderState/setHyperlinkMessage",
                    "Succesfully updated element hyperlink"
                );
                this.store.commit(
                    "pageBuilderState/setElementContainsHyperlink",
                    true
                );
                return;
            }

            // check if element contains child a tag
            if (hyperlink === null && urlInput.length !== 0) {
                // add a href
                if (parentHyperlink === null) {
                    const link = document.createElement("a");
                    link.href = urlInput;

                    // Conditionally set the target attribute if openHyperlinkInNewTab is true
                    if (openHyperlinkInNewTab === true) {
                        link.target = "_blank";
                    }

                    link.textContent = this.getElement.value.textContent;
                    this.getElement.value.textContent = "";
                    this.getElement.value.appendChild(link);
                    this.store.commit(
                        "pageBuilderState/setHyperlinkMessage",
                        "Successfully added hyperlink to element"
                    );
                    this.store.commit(
                        "pageBuilderState/setElementContainsHyperlink",
                        true
                    );

                    return;
                }
            }
            //
        }

        if (hyperlinkEnable === false && urlInput === "removeHyperlink") {
            if (!this.shouldRunMethods()) return;

            // To remove the added hyperlink tag
            const originalText = this.getElement.value.textContent;
            const textNode = document.createTextNode(originalText);
            this.getElement.value.textContent = "";
            this.getElement.value.appendChild(textNode);
            this.store.commit("pageBuilderState/setHyberlinkEnable", false);
            this.store.commit(
                "pageBuilderState/setElementContainsHyperlink",
                false
            );
        }
    }

    #checkForHyperlink(hyperlinkEnable, urlInput, openHyperlinkInNewTab) {
        console.log("#checkForHyperlink");
        if (!this.shouldRunMethods()) return;

        const hyperlink = this.getElement.value.querySelector("a");
        if (hyperlink !== null) {
            this.store.commit("pageBuilderState/setHyberlinkEnable", true);
            this.store.commit(
                "pageBuilderState/setElementContainsHyperlink",
                true
            );
            this.store.commit(
                "pageBuilderState/setHyperlinkInput",
                hyperlink.href
            );
            this.store.commit("pageBuilderState/setHyperlinkMessage", null);
            this.store.commit("pageBuilderState/setHyperlinkError", null);

            if (hyperlink.target === "_blank") {
                this.store.commit(
                    "pageBuilderState/setOpenHyperlinkInNewTab",
                    true
                );
            }
            if (hyperlink.target !== "_blank") {
                this.store.commit(
                    "pageBuilderState/setOpenHyperlinkInNewTab",
                    false
                );
            }

            return;
        }

        this.store.commit(
            "pageBuilderState/setElementContainsHyperlink",
            false
        );
        this.store.commit("pageBuilderState/setHyperlinkInput", "");
        this.store.commit("pageBuilderState/setHyperlinkError", null);
        this.store.commit("pageBuilderState/setHyperlinkMessage", null);
        this.store.commit("pageBuilderState/setHyberlinkEnable", false);
    }

    handleHyperlink(hyperlinkEnable, urlInput, openHyperlinkInNewTab) {
        console.log("handleHyperlink");
        if (!this.shouldRunMethods()) return;

        this.store.commit("pageBuilderState/setHyperlinkAbility", true);

        const parentHyperlink = this.getElement.value.closest("a");
        const hyperlink = this.getElement.value.querySelector("a");

        // handle case where parent element already has an a href tag
        // when clicking directly on a hyperlink
        if (parentHyperlink !== null) {
            this.store.commit("pageBuilderState/setHyperlinkAbility", false);
        }
        //
        const elementTag = this.getElement.value.tagName.toUpperCase();

        if (
            elementTag !== "P" &&
            elementTag !== "H1" &&
            elementTag !== "H2" &&
            elementTag !== "H3" &&
            elementTag !== "H4" &&
            elementTag !== "H5" &&
            elementTag !== "H6"
        ) {
            this.store.commit("pageBuilderState/setHyperlinkAbility", false);
        }

        if (hyperlinkEnable === undefined) {
            this.#checkForHyperlink(
                hyperlinkEnable,
                urlInput,
                openHyperlinkInNewTab
            );
            return;
        }

        this.#addHyperlinkToElement(
            hyperlinkEnable,
            urlInput,
            openHyperlinkInNewTab
        );
    }

    handlePageBuilderMethods() {
        if (!this.shouldRunMethods()) return;
        console.log("handlePageBuilderMethods ran.");

        // invoke methods
        // handle custom URL
        this.handleHyperlink();
        // handle opacity
        this.handleOpacity();
        // handle BG opacity
        this.handleBackgroundOpacity();
        // displayed image
        this.showBasePrimaryImage();
        // border style
        this.handleBorderStyle();
        // border width
        this.handleBorderWidth();
        // border color
        this.handleBorderColor();
        // border radius
        this.handleBorderRadiusGlobal();
        // border radius
        this.handleBorderRadiusTopLeft();
        // border radius
        this.handleBorderRadiusTopRight();
        // border radius
        this.handleBorderRadiusBottomleft();
        // border radius
        this.handleBorderRadiusBottomRight();
        // handle font size
        this.handleFontSize();
        // handle font weight
        this.handleFontWeight();
        // handle font family
        this.handleFontFamily();
        // handle font style
        this.handleFontStyle();
        // handle vertical padding
        this.handleVerticalPadding();
        // handle horizontal padding
        this.handleHorizontalPadding();
        // handle vertical margin
        this.handleVerticalMargin();
        // handle horizontal margin
        this.handleHorizontalMargin();
        // handle color
        this.handleBackgroundColor();
        // handle custom background color
        this.handleCustomBackgroundColor();
        // handle text color
        this.handleTextColor();
        // handle custom text color
        this.handleCustomTextColor();
        // handle classes
        this.currentClasses();
    }
}

const pageBuilder = new PageBuilder();

export default PageBuilder;
