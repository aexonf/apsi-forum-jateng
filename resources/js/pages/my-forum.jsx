import React from "react";
import { Trash2, PenLine, Eye, Heart, MessageSquare } from "lucide-react";
import Layout from "@/components/elements/layout";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";
import { Badge } from "@/components/ui/badge";

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
                    Forum
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
            <div className="flex w-full gap-10 my-4">
                <Button
                    className="w-full"
                    variant={
                        window.location.pathname == "/" ? "default" : "outline"
                    }
                    asChild
                >
                    <Link href="/">Publik</Link>
                </Button>
                <Button
                    className="w-full"
                    variant={
                        window.location.pathname == "/me"
                            ? "default"
                            : "outline"
                    }
                    asChild
                >
                    <Link href="/me">Saya</Link>
                </Button>
            </div>
            <div className="flex w-full gap-10 my-4">
                <Button className="w-full" variant="secondary" asChild>
                    <Link href="/new">Tambah Forum</Link>
                </Button>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                {Array.from({ length: 3 }).map((_, index) => (
                    <Link key={index} href="/post">
                        <Card
                            key={index}
                            className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group"
                        >
                            <div className="md:flex">
                                <div className="md:flex-shrink-0">
                                    <span className="object-cover md:w-48 rounded-md bg-muted w-[192px] h-[192px]" />
                                </div>
                                <div className="p-8 w-full">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-start">
                                            <img
                                                alt="pp"
                                                className="rounded-full"
                                                height="40"
                                                style={{
                                                    aspectRatio: "40/40",
                                                    objectFit: "cover",
                                                }}
                                                width="40"
                                            />
                                            <div className="ml-4">
                                                <div className="uppercase tracking-wide text-sm text-black dark:text-white font-semibold">
                                                    Adi Robi S.Pd
                                                </div>
                                                <div className="text-gray-400 dark:text-gray-300">
                                                    Kepala Sekolah SD Malangan
                                                </div>
                                            </div>
                                        </div>
                                        <div className="hidden group-hover:flex items-start gap-2 ">
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                asChild
                                            >
                                                <Link href="/edit">
                                                    <PenLine className="w-6 h-6 " />
                                                </Link>
                                            </Button>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                asChild
                                            >
                                                <Link
                                                    href="/delete"
                                                    method="DELETE"
                                                >
                                                    <Trash2 className="w-6 h-6 " />
                                                </Link>
                                            </Button>
                                        </div>
                                    </div>
                                    <p className="my-4 text-gray-500 dark:text-gray-300">
                                        Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit. Nobis sed repellendus
                                        necessitatibus libero exercitationem
                                        illo architecto animi molestias earum
                                        deserunt consectetur provident
                                        reiciendis quaerat, assumenda cum
                                        tenetur quasi nostrum. Hic.
                                    </p>
                                    <Badge variant={"success"}>Disetujui</Badge>
                                    <Badge variant={"warning"}>Pending</Badge>
                                    <Badge variant={"destructive"}>
                                        Ditolak
                                    </Badge>
                                    <div className="flex mt-6 justify-between items-center">
                                        <div className="flex space-x-4 text-gray-400 dark:text-gray-300">
                                            <div className="flex items-center text-red-500">
                                                <Eye className="size-5" />
                                                <span className="text-sm ml-1">
                                                    566
                                                </span>
                                            </div>
                                            <div className="flex items-center text-green-500">
                                                <Heart className="size-5" />
                                                <span className="text-sm ml-1 ">
                                                    241
                                                </span>
                                            </div>
                                            <div className="flex items-center text-blue-500">
                                                <MessageSquare className="size-5" />
                                                <span className="text-sm ml-1 ">
                                                    487
                                                </span>
                                            </div>
                                        </div>
                                        <div className="text-gray-400 dark:text-gray-300">
                                            {new Date().toLocaleString("id-ID")}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </Link>
                ))}
            </div>
        </Layout>
    );
}
