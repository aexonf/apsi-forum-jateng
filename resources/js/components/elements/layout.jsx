import React from "react";
import Header from "./header";
import { cn } from "../../lib/utils";

function Layout({ children, withHeader = true, className }) {
    return (
        <>
            {withHeader && <Header />}
            <main
                className={cn(
                    "container mx-auto max-w-3xl px-4 py-2 pt-[50px]",
                    className
                )}
            >
                {children}
            </main>
        </>
    );
}

export default Layout;
