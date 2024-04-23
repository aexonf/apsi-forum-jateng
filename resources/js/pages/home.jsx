import React, { useEffect, useState } from "react";
import { Eye, Heart, MessageSquare } from "lucide-react";
import Layout from "@/components/elements/layout";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";
import { toast } from "sonner";
import axios from "axios";
import { Skeleton } from "@/components/ui/skeleton";

const options = [
    {
        value: "newest",
        label: "Terbaru",
    },
    {
        value: "oldest",
        label: "Terlama",
    },
];

export default function Home() {
    const token = localStorage.getItem("token");
    const [selectedOption, setSelectedOption] = useState(options[0].value);

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);

    async function fetchDataByOption() {
        try {
            setLoading(true);
            const response = await axios.get(
                `/api/v1/forum?sort=${selectedOption}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            setData(response.data.data);
            setLoading(false);
        } catch (e) {
            toast.error("Ada yang salah.");
        }
    }

    useEffect(() => {
        fetchDataByOption();
    }, [selectedOption]);

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Forum
                </h3>
                <Select
                    defaultValue={options[0].value}
                    onValueChange={setSelectedOption}
                >
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
            {token ? (
                <>
                    <div className="flex w-full my-4">
                        <Button
                            className={`w-1/2 ${
                                window.location.pathname == "/"
                                    ? "bg-transparent border-b-2 border-primary rounded-none text-primary hover:bg-gray-100"
                                    : "bg-transparent border-b-2 border-gray-400 rounded-none text-gray-400 hover:bg-gray-100"
                            }`}
                        >
                            <Link href="/" className="w-full">
                                Publik
                            </Link>
                        </Button>
                        <Button
                            className={`w-1/2 ${
                                window.location.pathname == "/me"
                                    ? "bg-transparent border-b-2 border-primary rounded-none text-primary hover:bg-gray-100"
                                    : "bg-transparent border-b-2 border-gray-400 rounded-none text-gray-400 hover:bg-gray-100"
                            }`}
                        >
                            <Link href="/me" className="w-full">
                                Saya
                            </Link>
                        </Button>
                    </div>
                    <div className="flex w-full gap-10 my-4">
                        <Button className="w-full">
                            <Link href="/new">Tambah Forum</Link>
                        </Button>
                    </div>
                </>
            ) : null}
            <div className="w-full flex flex-col gap-y-4">
                {data.length > 0 ? (
                    data.map((item) => (
                        <Link key={item.id} href={`/post/${item.id}`}>
                            <Card className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group">
                                <div className="md:flex">
                                    <div className="md:flex-shrink-0">
                                        <span className="object-cover md:w-48 rounded-md bg-muted w-[192px] h-[192px]" />
                                    </div>
                                    <div className="p-8 w-full">
                                        <div className="flex items-start justify-between">
                                            <div className="flex items-start">
                                                <Avatar>
                                                    <AvatarImage
                                                        src={
                                                            item?.supervisor
                                                                ?.img_url
                                                                ? `/storage/${item?.supervisor?.img_url}`
                                                                : "/img/avatar/avatar-4.png"
                                                        }
                                                    />
                                                    <AvatarFallback></AvatarFallback>
                                                </Avatar>
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
                                        </div>
                                        <div className="my-4 max-h-64 overflow-hidden">
                                            <div
                                                dangerouslySetInnerHTML={{
                                                    __html: item.content,
                                                }}
                                            />
                                        </div>
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
                                                    <span className="text-sm ml-1">
                                                        {item.like_count}
                                                    </span>
                                                </div>
                                                <div className="flex items-center text-blue-500">
                                                    <MessageSquare className="size-5" />
                                                    <span className="text-sm ml-1">
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
                ) : loading ? (
                    <>
                        <Skeleton className="w-full h-64" />
                        <Skeleton className="w-full h-64" />
                        <Skeleton className="w-full h-64" />
                    </>
                ) : (
                    <div className="text-center mt-40">
                        <h3 className="font-semibold text-3xl">
                            Belum Ada Data
                        </h3>
                    </div>
                )}
            </div>
        </Layout>
    );
}
