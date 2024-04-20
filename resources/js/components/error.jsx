import { ServerOffIcon } from "lucide-react";
import React from "react";
import { Button } from "@/components/ui/button";
import { Link } from "@inertiajs/inertia-react";

export default function Error() {
    return (
        <div className="flex flex-col items-center space-y-4 mt-32 text-center">
            <ServerOffIcon className="h-10 w-10" />
            <div className="text-center space-y-2">
                <h1 className="text-3xl font-bold tracking-tighter">
                    Uh oh! Something went wrong.
                </h1>
            </div>
            <div className="w-full max-w-sm space-y-2 ">
                <p className="text-sm leading-snug">
                    We encountered an error while trying to fetch the data.
                </p>
                <p className="text-sm leading-snug">
                    Please check your internet connection and try again.
                </p>
            </div>
            <div className="flex space-x-4">
                <Button
                    variant="outline"
                    onClick={() => {
                        window.location.reload();
                    }}
                >
                    Try again
                </Button>
                <Button variant="ghost" asChild>
                    <Link href="/">Go back</Link>
                </Button>
            </div>
        </div>
    );
}
