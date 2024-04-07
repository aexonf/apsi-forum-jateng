import Layout from "@/components/elements/layout";
import { cn } from "../lib/utils";
import { Link } from "@inertiajs/inertia-react";
import React from "react";

export function MainNav() {
    return (
        <Layout>
            <div className="mr-4 hidden md:flex">
                <nav className="flex items-center gap-4 text-sm lg:gap-6">
                    <Link
                        href="/docs"
                        className={cn(
                            "transition-colors hover:text-foreground/80",
                            pathname === "/docs"
                                ? "text-foreground"
                                : "text-foreground/60"
                        )}
                    >
                        Docs
                    </Link>
                </nav>
            </div>
        </Layout>
    );
}
