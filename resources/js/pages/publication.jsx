import React from "react";
import { Eye, Heart, MessageSquare } from "lucide-react";
import Layout from "@/components/elements/layout";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";

const options = [
    {
        value: "terbaru",
        label: "Terbaru",
    },
    {
        value: "terlama",
        label: "Terlama",
    },
];

export default function Home() {
    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Publikasi
                </h3>
                <Select defaultValue={"terbaru"}>
                    <SelectTrigger
                        aria-label="Select status"
                        className="w-max gap-4"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {options.map((option) => (
                            <SelectItem key={option.value} value={option.value}>
                                {option.label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>
            <div className="w-full">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-lg">
                            1. Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Sint tempora repellat nostrum
                            minus a dolor esse veniam iure labore. Sunt non,
                            odit doloremque qui quod consequuntur quasi
                            laboriosam quae perspiciatis.
                        </CardTitle>
                    </CardHeader>
                    <CardFooter className="w-full justify-end">
                        <div className="flex items-center text-red-500">
                            <Eye className="size-5" />
                            <span className="text-sm ml-1">0</span>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </Layout>
    );
}
