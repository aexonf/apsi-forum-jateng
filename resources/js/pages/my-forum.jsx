import React, { useEffect, useState } from "react";
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
import axios from "axios";
import { toast } from "sonner";
import { Skeleton } from "@/components/ui/skeleton";

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
    const token = localStorage.getItem("token");
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);

    useEffect(() => {
        async function fetchProfile() {
            try {
                setLoading(true);
                const response = await axios.get("/api/v1/forum/me", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                setData(response.data.data);
                setLoading(false);
                console.log(response);
            } catch (e) {
                toast.error("Ada yang salah.");
            }
        }
        fetchProfile();
    }, []);

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
                {loading ? (
                    <>
                        <Skeleton className="w-full h-64" />
                        <Skeleton className="w-full h-64" />
                        <Skeleton className="w-full h-64" />
                    </>
                ) : (
                    data?.map((item) => (
                        <Link key={item.id} href={`/post/${item.id}`}>
                            <Card className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group">
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
                                                    <div className="tracking-wide text-sm text-black dark:text-white font-semibold">
                                                        {item?.supervisor?.name}
                                                    </div>
                                                    <div className="text-gray-400 dark:text-gray-300">
                                                        {
                                                            item?.supervisor
                                                                ?.label
                                                        }
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
                                        <div className="my-4 text-gray-500 dark:text-gray-300">
                                            <div
                                                dangerouslySetInnerHTML={{
                                                    __html: item.content,
                                                }}
                                            />
                                        </div>
                                        {item?.status == "pending" && (
                                            <Badge variant={"warning"}>
                                                Pending
                                            </Badge>
                                        )}
                                        {item?.status == "approved" && (
                                            <Badge variant={"success"}>
                                                Disetujui
                                            </Badge>
                                        )}
                                        {item?.status == "rejected" && (
                                            <Badge variant={"destructive"}>
                                                Ditolak
                                            </Badge>
                                        )}
                                        <div className="flex mt-6 justify-between items-center">
                                            <div className="flex space-x-4 text-gray-400 dark:text-gray-300">
                                                <div className="flex items-center text-red-500">
                                                    <Eye className="size-5" />
                                                    <span className="text-sm ml-1">
                                                        {item.view_count}
                                                    </span>
                                                </div>
                                                <div className="flex items-center text-green-500">
                                                    <Heart className="size-5" />
                                                    <span className="text-sm ml-1 ">
                                                        {item.like_count}
                                                    </span>
                                                </div>
                                                <div className="flex items-center text-blue-500">
                                                    <MessageSquare className="size-5" />
                                                    <span className="text-sm ml-1 ">
                                                        {item.comment_count}
                                                    </span>
                                                </div>
                                            </div>
                                            <div className="text-gray-400 dark:text-gray-300">
                                                {new Date(
                                                    item.created_at
                                                ).toLocaleString("id-ID")}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Card>
                        </Link>
                    ))
                )}
            </div>
        </Layout>
    );
}
