import React from "react";
import { createRoot } from "react-dom/client";

import { createInertiaApp } from "@inertiajs/inertia-react";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

const root = createRoot(document.getElementById("app"));

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.jsx`,
            import.meta.glob("./pages/**/*.jsx")
        ),
    setup({ App, props }) {
        root.render(<App {...props} />);
    },
});

InertiaProgress.init();
