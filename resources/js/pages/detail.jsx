import React from "react";
import { ChevronLeft } from "lucide-react";
import Layout from "@/components/elements/layout";
import { Trash2, PenLine, Eye, Heart, MessageSquare } from "lucide-react";
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
import { Textarea } from "@/components/ui/textarea";
import { Separator } from "@/components/ui/separator";

export default function Home() {
    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Detail Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card
                    key={1}
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
                            </div>
                            <p className="my-4 text-gray-500 dark:text-gray-300">
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Nobis sed repellendus
                                necessitatibus libero exercitationem illo
                                architecto animi molestias earum deserunt
                                consectetur provident reiciendis quaerat,
                                assumenda cum tenetur quasi nostrum. Hic.
                            </p>
                            <div className="w-full min-h-36 bg-muted"></div>
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
                <Card
                    key={2}
                    className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group"
                >
                    <div className="md:flex">
                        <div className="p-8 w-full">
                            <div className="w-full flex items-start justify-between mb-4">
                                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                                    Tanggapan
                                </h3>
                                <Button>Kirim</Button>
                            </div>
                            <Textarea className="my-4" placeholder="..." />
                            <Separator className="my-8" />
                            <div className="flex items-start justify-between my-4">
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
                            </div>
                            <p className="mt-4 text-gray-500 dark:text-gray-300">
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Nobis sed repellendus
                                necessitatibus libero exercitationem illo
                                architecto animi molestias earum deserunt
                                consectetur provident reiciendis quaerat,
                                assumenda cum tenetur quasi nostrum. Hic.
                            </p>
                        </div>
                    </div>
                </Card>
            </div>
        </Layout>
    );
}
